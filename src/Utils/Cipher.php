<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Http\Request;
use Jmhc\Restful\Utils\Cipher\Runtime;

class Cipher
{
    /**
     * @var array
     */
    protected static $config;

    /**
     * 请求
     * @param $params
     * @return string
     */
    public static function request($params)
    {
        if (static::isExec()) {
            return Runtime::getInstance(static::getConfig())
                ->decrypt($params);
        }

        return $params;
    }

    /**
     * 响应
     * @param array $data
     * @return array|string
     */
    public static function response(array $data)
    {
        if (static::isExec()) {
            return Runtime::getInstance(static::getConfig())
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
        return ! Env::get(
            'jmhc.request.debug',
            true
        );
    }

    /**
     * 获取配置
     * @return array
     */
    protected static function getConfig()
    {
        if (empty(static::$config)) {
            static::$config = Env::get('jmhc.request', []);
        }

        return static::$config;
    }
}
