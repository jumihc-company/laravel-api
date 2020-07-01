<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Middleware;

/**
 * trim 请求参数中间件
 * @package Jmhc\Restful\Middleware
 */
class TrimStringsMiddleware extends TransformsRequestMiddleware
{
    /**
     * 不需要 trim 的字段
     * @var array
     */
    protected $except = [];

    /**
     * trim 请求参数
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->except, true)) {
            return $value;
        }

        return is_string($value) ? trim($value) : $value;
    }
}
