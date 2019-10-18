<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Contracts\Container\BindingResolutionException;
use Jmhc\Restful\Utils\Log\FileHandler;

class Log
{
    /**
     * 配置
     * @var array
     */
    protected static $config = [];

    /**
     * 设置配置
     * @param array $config
     * @return static
     */
    public static function setConfig(array $config)
    {
        static::$config = $config;
        return new static();
    }

    /**
     * 调试日志
     * @param string $name
     * @param string $msg
     * @param mixed ...$params
     * @return bool
     * @throws BindingResolutionException
     */
    public static function debug(string $name, string $msg, ...$params)
    {
        if (static::getFileHandler()->isDebug()) {
            return static::save($name, $msg, ...$params);
        }

        // 重置配置
        static::$config = [];

        return true;
    }

    /**
     * 保存
     * @param string $name
     * @param string $msg
     * @param mixed ...$params
     * @return mixed
     * @throws BindingResolutionException
     */
    public static function save(string $name, string $msg, ...$params)
    {
        if (! empty($params)) {
            $msg = sprintf($msg, ...$params);
        }

        $result = static::getFileHandler()->write($name, $msg);

        // 重置配置
        static::$config = [];

        return $result;
    }

    /**
     * 获取文件操作
     * @return FileHandler
     * @throws BindingResolutionException
     */
    protected static function getFileHandler()
    {
        if (! empty(static::$config)) {
            return FileHandler::getInstance([
                'config' => static::$config,
            ]);
        }

        return FileHandler::getInstance();
    }
}
