<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest as BaseTransformsRequest;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * 转换请求参数中间件
 * @package Jmhc\Restful\Middleware
 */
class TransformsRequestMiddleware extends BaseTransformsRequest
{
    protected function clean($request)
    {
        $this->cleanParameterBag(
            new ParameterBag($request->params ?? [])
        );
    }
}
