<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * 跨域中间件
 * @package Jmhc\Restful\Middleware
 */
class CorsMiddleware
{
    /**
     * 跨域响应状态码
     * @var int
     */
    protected $statusCode = 204;

    public function handle(Request $request, Closure $next)
    {
        $headers = config('jmhc-api.cors', []);

        if ($request->getMethod() === 'OPTIONS') {
            return response('', $this->statusCode, $headers);
        }

        return $next($request)->withHeaders($headers);
    }
}
