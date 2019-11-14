<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Contracts\Container\BindingResolutionException;
use Jmhc\Restful\Utils\Cipher\Runtime;

/**
 * 运行加、解密
 * @package Jmhc\Restful\Utils
 */
class Cipher
{
    /**
     * 请求
     * @param $params
     * @return string
     * @throws BindingResolutionException
     */
    public static function request($params)
    {
        if (static::isExec()) {
            return Runtime::getInstance()
                ->decrypt($params);
        }

        return $params;
    }

    /**
     * 响应
     * @param array $data
     * @return array|string
     * @throws BindingResolutionException
     */
    public static function response(array $data)
    {
        if (static::isExec()) {
            return Runtime::getInstance()
                ->encrypt(json_encode($data));
        }

        return $data;
    }

    /**
     * 是否运行
     * @return bool
     */
    protected static function isExec()
    {
        return ! config(
            'jmhc-api.runtime.debug',
            true
        );
    }
}
