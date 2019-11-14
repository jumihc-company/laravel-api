<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Middleware;

/**
 * 转换空字符串为 null
 * @package Jmhc\Restful\Middleware
 */
class ConvertEmptyStringsToNullMiddleware extends TransformsRequestMiddleware
{
    protected function transform($key, $value)
    {
        return is_string($value) && $value === '' ? null : $value;
    }
}
