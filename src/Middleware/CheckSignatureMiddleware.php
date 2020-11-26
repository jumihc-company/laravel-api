<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Redis\Connections\PhpRedisConnection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Jmhc\Log\Log;
use Jmhc\Restful\Contracts\RequestParamsInterface;
use Jmhc\Restful\Exceptions\ResultException;
use Jmhc\Restful\Traits\ResultThrowTrait;
use Jmhc\Restful\Utils\Signature;
use Jmhc\Support\Helper\RedisConnectionHelper;
use Jmhc\Support\Utils\Helper;

/**
 * 检测签名中间件
 * @package Jmhc\Restful\Middleware
 */
class CheckSignatureMiddleware
{
    use ResultThrowTrait;

    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        '*.check' => 'bail|required|boolean',
        '*.key' => 'bail|required|string',
    ];

    /**
     * 缓存标识
     * @var string
     */
    protected $cacheKey = 'nonce-list';

    /**
     * 多场景
     * @var array
     */
    protected $scenes = [];

    public function handle(Request $request, Closure $next, ...$scenes)
    {
        // 场景存在
        if (! empty($scenes)) {
            $this->scenes = $scenes;
        }

        // 验证
        $this->check($request);

        return $next($request);
    }

    /**
     * 加载配置
     * @return array
     */
    protected function withConfig()
    {
        return [
            // 是否检测签名
            'check' => config('jmhc-api.signature.check'),
            // 签名秘钥
            'key' => config('jmhc-api.signature.key', ''),
        ];
    }

    /**
     * 验证
     * @param Request $request
     * @throws ResultException
     * @throws ValidationException
     */
    private function check(Request $request)
    {
        // 获取配置
        $configs = $this->getConfigs();
        if (empty($configs)) {
            return;
        }

        // 时间
        $time = time();
        // 验证时间戳
        $timestamp = $request->originParams['timestamp'] ?? 0;
        $this->validateTimestamp(
            config('jmhc-api.signature.check_timestamp', true),
            $time,
            $timestamp,
            config('jmhc-api.signature.timestamp_timeout', 60),
        );

        // 缓存链接
        $connection = RedisConnectionHelper::getPhpRedis();
        // 验证随机数
        $nonce = $request->originParams['nonce'] ?? '';
        $this->validateNonce($connection, $nonce);
        // 添加随机数
        $this->addNonceCache($connection, $nonce);

        // 签名数据
        $sign = $request->originParams['sign'] ?? '';
        $data = app()->get(RequestParamsInterface::class)->toArray();
        $data['timestamp'] = $timestamp;
        $data['nonce'] = $nonce;
        // 验证签名
        $validateSign = $this->validateSign($request, $sign, $data, $configs);
        if ($validateSign === true) {
            return;
        }

        // 签名验证失败记录
        Log::name('signature.error')
            ->withDateToName()
            ->withMessageLineBreak()
            ->info($validateSign);
        $this->error(jmhc_api_lang_messages_trans('signature_verification_failed'));
    }

    /**
     * 获取配置
     * @return array
     * @throws ValidationException
     */
    private function getConfigs()
    {
        // 配置
        $data = $this->withConfig();
        if (Helper::isOneDimensional($data)) {
            $data = [$data];
        }

        // 验证数据
        $data = Validator::make($data, $this->rule)->validated();
        if (empty($data)) {
            return [];
        }

        $res = [];
        foreach ($data as $k => $v) {
            // 多场景存在但不在场景中或不需要验证
            if (! empty($this->scenes) && ! in_array($k, $this->scenes) || ! Helper::boolean($v['check'])) {
                continue;
            }

            $res[$k] = $v;
        }

        return $res;
    }

    /**
     * 验证时间戳
     * @param bool $checkTimestamp
     * @param int $time
     * @param int $timestamp
     * @param int $timeout
     * @throws ResultException
     */
    protected function validateTimestamp(bool $checkTimestamp, int $time, int $timestamp, int $timeout)
    {
        // 跳过验证
        if (! $checkTimestamp) {
            return;
        }

        // 时间过大
        if ($timestamp > ($time + $timeout)) {
            $this->error(jmhc_api_lang_messages_trans('please_calibrate_the_time'));
        }

        // 时间过小
        if (($timestamp + $timeout) < $time) {
            $this->error(jmhc_api_lang_messages_trans('request_expired'));
        }
    }

    /**
     * 验证随机数
     * @param PhpRedisConnection $connection
     * @param string $nonce
     * @throws ResultException
     */
    private function validateNonce(PhpRedisConnection $connection, string $nonce)
    {
        // 验证是否存在
        if (empty($nonce)) {
            $this->error(jmhc_api_lang_messages_trans('request_random_number_no_exist'));
        }

        // 验证是否在列表
        if (in_array($nonce, $this->getNonceCacheList($connection))) {
            $this->error(jmhc_api_lang_messages_trans('request_random_number_already_exist'));
        }
    }

    /**
     * 获取随机数缓存列表
     * @param PhpRedisConnection $connection
     * @return array
     */
    private function getNonceCacheList(PhpRedisConnection $connection)
    {
        return $connection->lrange($this->cacheKey, 0, -1);
    }

    /**
     * 添加随机数缓存
     * @param PhpRedisConnection $connection
     * @param string $nonce
     */
    private function addNonceCache(PhpRedisConnection $connection, string $nonce)
    {
        // 添加随机数
        $connection->lpush($this->cacheKey, $nonce);

        // 设置过期时间
        if ($connection->ttl($this->cacheKey) == -1) {
            $connection->expire($this->cacheKey, config('jmhc-api.signature.nonce_expire', 60));
        }
    }

    /**
     * 验证签名
     * @param Request $request
     * @param string $sign
     * @param array $data
     * @param array $configs
     * @return bool|string
     */
    private function validateSign(Request $request, string $sign, array $data, array $configs)
    {
        // 错误消息
        $msg = <<<EOF
ip: {$request->ip()}
url: {$request->fullUrl()}
method: {$request->method()}
请求签名: {$sign}
EOF;

        // 验证签名
        $num = 1;
        $res = false;
        foreach ($configs as $config) {
            // 签名数据
            $_signData = Signature::sign($data, $config['key'], false);
            if ($sign === $_signData['sign']) {
                $res = true;
                break;
            }

            // 获取签名错误消息
            $msg .= $this->getSignErrorMsg($_signData, $num == 1, $config['key']);

            $num ++;
        }

        return $res ?: $msg;
    }

    /**
     * 获取签名错误消息
     * @param array $signData
     * @param bool $isFirst
     * @param string $key
     * @return string
     */
    private function getSignErrorMsg(array $signData, bool $isFirst, string $key)
    {
        $res = '';

        // 首次
        if ($isFirst) {
            $origin = json_encode($signData['origin'], JSON_UNESCAPED_UNICODE);
            $sort = json_encode($signData['sort'], JSON_UNESCAPED_UNICODE);
            $res .= <<<EOF

源数据: {$origin}
排序后数据: {$sort}
构造签名字符串: {$signData['build']}
EOF;
        }

        return $res . <<<EOF

待签名字符串({$key}): {$signData['wait_str']}
签名结果({$key}): {$signData['sign']}
EOF;
    }
}
