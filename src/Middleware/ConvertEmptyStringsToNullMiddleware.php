<?php
/**
 * User: YL
 * Date: 2020/07/01
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
