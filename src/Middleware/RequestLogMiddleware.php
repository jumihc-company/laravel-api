<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Log\Log;
use Jmhc\Support\Utils\RequestInfo;

/**
 * 请求日志中间件
 * @package Jmhc\Restful\Middleware
 */
class RequestLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 记录请求日志
        Log::dir('request')
            ->name('')
            ->withDateToName()
            ->withMessageLineBreak()
            ->debug(RequestInfo::get());

        return $next($request);
    }
}
