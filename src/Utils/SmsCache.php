<?php
/**
 * User: YL
 * Date: 2019/12/23
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Redis\Connections\Connection;
use Jmhc\Restful\Traits\InstanceTrait;
use Jmhc\Restful\Traits\RedisHandlerTrait;
use Jmhc\Sms\Contracts\CacheInterface;
use Redis;

class SmsCache implements CacheInterface
{
    use InstanceTrait;
    use RedisHandlerTrait;

    /**
     * @var Connection|Redis
     */
    protected $redis;

    public function __construct()
    {
        $this->redis = $this->getPhpRedisHandler();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): array
    {
        return $this->redis->hGetAll($key);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, array $data): bool
    {
        return $this->redis->hMSet($key, $data);
    }

    /**
     * @inheritDoc
     */
    public function expire(string $key, int $ttl): bool
    {
        return $this->redis->expire($key, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool
    {
        return !! $this->redis->exists($key);
    }

    /**
     * @inheritDoc
     */
    public function del(string $key): bool
    {
        return !! $this->redis->del($key);
    }
}
