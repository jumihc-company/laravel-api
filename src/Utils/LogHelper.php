<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Contracts\Container\BindingResolutionException;
use Throwable;

class LogHelper
{
    /**
     * @var string
     */
    protected static $dir = 'storage/logs/%s';

    /**
     * 异常保存
     * @param string $name
     * @param Throwable $e
     * @return mixed
     * @throws BindingResolutionException
     */
    public static function throwableSave(string $name, Throwable $e)
    {
        return Log::save(
            $name,
            $e->getMessage() . PHP_EOL . $e->getTraceAsString()
        );
    }

    /**
     * 请求日志
     * @param array $config
     * @return Log
     */
    public static function request(array $config = [])
    {
        return Log::setConfig(array_map([
            'path' => static::dir('request'),
        ], $config));
    }

    /**
     * 队列日志
     * @param array $config
     * @return Log
     */
    public static function queue(array $config = [])
    {
        return Log::setConfig(array_map([
            'path' => static::dir('queue'),
        ], $config));
    }

    /**
     * 保存路径
     * @param string $dir
     * @return string
     */
    protected static function dir(string $dir)
    {
        return sprintf(static::$dir, $dir);
    }
}
