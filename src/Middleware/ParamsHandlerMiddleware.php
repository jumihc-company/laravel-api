<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Restful\Contracts\RequestParamsInterface;
use Jmhc\Restful\Utils\RequestParams;
use Jmhc\Support\Utils\Collection;

/**
 * 请求参数处理中间件
 * @package Jmhc\Restful\Middleware
 */
class ParamsHandlerMiddleware
{
    /**
     * 过滤键
     * @var array
     */
    protected $filter = ['sign', 'nonce', 'timestamp', 'file'];

    public function handle(Request $request, Closure $next)
    {
        // 原请求参数
        $request->originParams = RequestParams::run($request);

        // 过滤后参数
        $params = array_filter($request->originParams, function ($k) {
            return ! in_array($k, $this->filter);
        }, ARRAY_FILTER_USE_KEY);
        app()->instance(RequestParamsInterface::class, new Collection($params));

        return $next($request);
    }
}
