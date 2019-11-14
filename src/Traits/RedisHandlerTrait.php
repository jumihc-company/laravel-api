<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Traits;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

/**
 * Redis 操作句柄 trait
 * @package Jmhc\Restful\Traits
 */
trait RedisHandlerTrait
{
    /**
     * 获取 redis 操作句柄
     * @return Connection
     */
    protected function getRedisHandler()
    {
        return Redis::connection();
    }

    /**
     * 获取无前缀 redis 操作句柄
     * @return Connection
     */
    protected function getNoPrefixRedisHandler()
    {
        $id = 'redis.no.prefix';

        if (! app()->has($id)) {
            $config = config('database.redis', []);
            $config['options']['prefix'] = '';

            $redis = new RedisManager(
                app('app'),
                Arr::pull($config, 'client', 'phpredis'),
                $config
            );
            app()->instance($id, $redis->connection());
        }

        return app()->get($id);
    }

    /**
     * 获取 phpredis 驱动的操作句柄
     * @return Connection|\Redis
     */
    public function getPhpRedisHandler()
    {
        $id = 'php.redis';

        if (! app()->has($id)) {
            $redis = new RedisManager(
                app('app'),
                'phpredis',
                config('database.redis', [])
            );
            app()->instance($id, $redis->connection());
        }

        return app()->get($id);
    }

    /**
     * 获取无前缀 phpredis 驱动的操作句柄
     * @return Connection|\Redis
     */
    public function getNoPrefixPhpRedisHandler()
    {
        $id = 'php.redis.no.prefix';

        if (! app()->has($id)) {
            $config = config('database.redis', []);
            $config['options']['prefix'] = '';

            $redis = new RedisManager(
                app('app'),
                'phpredis',
                $config
            );
            app()->instance($id, $redis->connection());
        }

        return app()->get($id);
    }
}
