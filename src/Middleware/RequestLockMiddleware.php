<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Jmhc\Restful\Contracts\RequestParamsInterface;
use Jmhc\Restful\Contracts\ResultCodeInterface;
use Jmhc\Restful\Traits\ResultThrowTrait;
use Jmhc\Support\Utils\Helper;

/**
 * 请求锁定中间件
 * @package Jmhc\Restful\Middleware
 */
class RequestLockMiddleware
{
    use ResultThrowTrait;

    public function handle(Request $request, Closure $next, $useSign = false)
    {
        // 跨域请求
        if ($request->getMethod() === 'OPTIONS') {
            return $next($request);
        }

        // 请求锁定对象实例
        $request->requestLock = Cache::store(
            config('jmhc-api.request_lock.driver', 'redis')
        )->lock(
            $this->getLockKey($request, Helper::boolean($useSign)),
            config('jmhc-api.request_lock.seconds', 5)
        );

        if (! $request->requestLock->get()) {
            $this->error(
                jmhc_api_lang_messages_trans('request_lock_prompt'),
                ResultCodeInterface::REQUEST_LOCKED
            );
        }

        return $next($request);
    }

    /**
     * 获取锁定标识
     * @param Request $request
     * @param bool $useSign
     * @return string|null
     */
    protected function getLockKey(Request $request, bool $useSign)
    {
        $sign = $useSign ? $request->input('sign', '') : '';
        return 'lock-' . md5($request->ip() . $request->path() . $sign . json_encode(app()->get(RequestParamsInterface::class)));
    }
}
