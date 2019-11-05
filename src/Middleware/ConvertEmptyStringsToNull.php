<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Middleware;

class ConvertEmptyStringsToNull
{
    /**
     * 转换空字符串为null
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        return is_string($value) && $value === '' ? null : $value;
    }
}
