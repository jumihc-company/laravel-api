<?php
/**
 * User: YL
 * Date: 2019/10/23
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Jmhc\Restful\ResultCode;
use Jmhc\Restful\Traits\ResultThrowTrait;

/**
 * 请求锁定中间件
 * @package Jmhc\Restful\Middleware
 */
class RequestLockMiddleware
{
    use ResultThrowTrait;

    public function handle(Request $request, Closure $next)
    {
        // 跨域请求
        if ($request->getMethod() === 'OPTIONS') {
            return $next($request);
        }

        $request->requestLock = Cache::store(
            config('jmhc-api.request_lock.driver', 'redis')
        )->lock(
            $this->getLockKey($request),
            config('jmhc-api.request_lock.seconds', 5)
        );

        if (! $request->requestLock->get()) {
            $this->error(
                config('jmhc-api.request_lock.tips', '请求已被锁定，请稍后重试~'),
                ResultCode::REQUEST_LOCKED
            );
        }

        return $next($request);
    }

    /**
     * 获取锁定标识
     * @param Request $request
     * @return string|null
     */
    protected function getLockKey(Request $request)
    {
        return 'lock-' . md5($request->ip() . $request->path() . json_encode($request->params));
    }
}
