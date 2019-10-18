<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Redis\Connections\Connection;
use Jmhc\Restful\Traits\Instance;
use Jmhc\Restful\Traits\RedisHandler;

class Sdl
{
    use Instance;
    use RedisHandler;

    /**
     * @var Connection
     */
    protected $handler;

    protected static $cacheKey = 'sdl_%d';
    protected static $cacheTmpKey = 'sdl_tmp_%d';

    public function __construct()
    {
        $this->handler = static::getPhpRedisHandler();
    }

    /**
     * 获取缓存数据
     * @param int $id
     * @return array
     */
    public function get(int $id)
    {
        return array_filter([
            $this->handler->get($this->getCacheKey($id)),
            $this->handler->get($this->getCacheTmpKey($id)),
        ]);
    }

    /**
     * 设置缓存数据
     * @param int $id
     * @param string $token
     * @param string $oldToken
     * @return bool
     */
    public function set(int $id, string $token, string $oldToken = '')
    {
        $this->handler->set($this->getCacheKey($id), $token);

        // 旧token存在
        if (! empty($oldToken)) {
            $this->handler->setex($this->getCacheTmpKey($id), Env::get('jmhc.sdl_tmp_expire', 10), $oldToken);
        }

        return true;
    }

    /**
     * 验证是否通过
     * @param int $id
     * @param string $token
     * @return bool
     */
    public function verify(int $id, string $token)
    {
        return in_array($token, $this->get($id));
    }

    /**
     * 获取缓存key
     * @param int $id
     * @return string
     */
    protected function getCacheKey(int $id)
    {
        return sprintf(static::$cacheKey, $id);
    }

    /**
     * 获取临时key
     * @param int $id
     * @return string
     */
    protected function getCacheTmpKey(int $id)
    {
        return sprintf(static::$cacheTmpKey, $id);
    }
}
