<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Restful\Utils\Cipher;

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
        // 是否直接存在json格式的params参数
        $jsonParams = json_decode($request->input('params', ''), true);

        // 请求参数
        $params = $jsonParams ?? $request->all();

        // 请求解密
        if ($request->exists('params') && ! $jsonParams) {
            $params = Cipher::request($request->input('params'));
        }

        // 原请求参数
        $request->originParams = $params;

        // 过滤后参数
        $request->params = array_filter($params, function ($k) {
            return ! in_array($k, $this->filter);
        }, ARRAY_FILTER_USE_KEY);

        return $next($request);
    }
}
