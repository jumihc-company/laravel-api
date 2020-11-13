<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Caches;

use Illuminate\Redis\Connections\PhpRedisConnection;
use Jmhc\Support\Helper\RedisConnectionHelper;
use Jmhc\Support\Traits\InstanceTrait;

/**
 * 基础缓存
 * @package Jmhc\Restful\Caches
 */
class BaseCache
{
    use InstanceTrait;

    /**
     * @var PhpRedisConnection
     */
    protected $connection;

    public function __construct()
    {
        $this->connection = RedisConnectionHelper::getPhpRedis();
    }

    /**
     * 获取过期时间
     * @return int
     */
    protected function getExpireTime()
    {
        $current = date('Y-m-d');
        return strtotime("$current +1 day") - time();
    }
}
