<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Caches;

use Jmhc\Support\Traits\InstanceTrait;
use Jmhc\Support\Traits\RedisHandlerTrait;

/**
 * 基础缓存
 * @package Jmhc\Restful\Caches
 */
class BaseCache
{
    use InstanceTrait;
    use RedisHandlerTrait;

    /**
     * @var \Illuminate\Redis\Connections\Connection|\Redis
     */
    protected $handler;

    public function __construct()
    {
        $this->handler = $this->getPhpRedisHandler();
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
