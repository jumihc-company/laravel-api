<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Log\Log;
use Jmhc\Restful\Contracts\RequestParamsInterface;
use Jmhc\Restful\Exceptions\ResultException;
use Jmhc\Restful\Traits\ResultThrowTrait;
use Jmhc\Restful\Utils\Signature;
use Jmhc\Support\Helper\RedisConnectionHelper;

/**
 * 检测签名中间件
 * @package Jmhc\Restful\Middleware
 */
class CheckSignatureMiddleware
{
    use ResultThrowTrait;

    public function handle(Request $request, Closure $next)
    {
        // 加载配置
        $config = $this->withConfig();
        if (! $config['check']) {
            return $next($request);
        }

        // 判断时间戳是否超时
        $timestamp = $request->originParams['timestamp'] ?? 0;
        $time = time();
        $this->validateTimestamp($config['check_timestamp'], $time, $timestamp, $config['timestamp_timeout']);

        // 判断随机数是否有效
        $nonce = $request->originParams['nonce'] ?? '';
        $this->validateNonce($nonce, $timestamp, $config['timestamp_timeout']);

        // 验证签名是否正确
        $sign = $request->originParams['sign'] ?? '';
        $data = app()->get(RequestParamsInterface::class)->toArray();
        $data['timestamp'] = $timestamp;
        $data['nonce'] = $nonce;
        if (! Signature::verify($sign, $data, $config['key'])) {
            // 签名验证失败记录
            Log::name('signature.error')
                ->withDateToName()
                ->info(
                    $this->getSignatureErrorMsg($request, $sign, $data, $config['key'])
                );
                $this->error(jmhc_api_lang_messages_trans('signature_verification_failed'));
        }

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
            // 签名时间戳超时（秒）
            'timestamp_timeout' => config('jmhc-api.signature.timestamp_timeout', 60),
            // 验证时间戳
            'check_timestamp' => config('jmhc-api.signature.check_timestamp', true),
        ];
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
        if (! $checkTimestamp) {
            return;
        }

        if ($timestamp > ($time + $timeout)) {
            $this->error(jmhc_api_lang_messages_trans('please_calibrate_the_time'));
        }

        if (($timestamp + $timeout) < $time) {
            $this->error(jmhc_api_lang_messages_trans('request_expired'));
        }
    }

    /**
     * 验证随机数
     * @param string $nonce
     * @param int $timestamp
     * @param int $timeout
     * @throws ResultException
     */
    protected function validateNonce(string $nonce, int $timestamp, int $timeout)
    {
        if (empty($nonce)) {
            $this->error(jmhc_api_lang_messages_trans('request_random_number_no_exist'));
        }

        // 保存的随机数
        $nonce .= $timestamp;

        // 缓存链接
        $connection = RedisConnectionHelper::getPhpRedis();
        // 缓存标识
        $cacheKey = 'nonce-list-' . $timeout;

        // 获取已缓存随机数列表
        $list = $connection->lrange($cacheKey, 0, -1);
        if (in_array($nonce, $list)) {
            $this->error(jmhc_api_lang_messages_trans('request_random_number_already_exist'));
        }

        // 添加随机数
        $connection->lpush($cacheKey, $nonce);

        // 设置过期时间
        if ($connection->ttl($cacheKey) == -1) {
            $connection->expire($cacheKey, $timeout);
        }
    }

    /**
     * 获取签名错误消息
     * @param Request $request
     * @param string $sign
     * @param array $data
     * @param string $key
     * @return string
     */
    protected function getSignatureErrorMsg(Request $request, string $sign, array $data, string $key)
    {
        // 签名数据
        $signData = Signature::sign($data, $key, false);
        $origin = json_encode($signData['origin'], JSON_UNESCAPED_UNICODE);
        $sort = json_encode($signData['sort'], JSON_UNESCAPED_UNICODE);

        return <<<EOL
ip: {$request->ip()}
url: {$request->fullUrl()}
method: {$request->method()}
源数据: {$origin}
排序后数据: {$sort}
构造签名字符串: {$signData['build']}
待签名数据: {$signData['wait_str']}
签名结果: {$signData['sign']}
请求签名: {$sign}
EOL;
    }
}
