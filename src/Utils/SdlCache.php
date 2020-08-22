<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Redis\Connections\Connection;
use Jmhc\Restful\Contracts\ConstAttributeInterface;
use Jmhc\Support\Traits\InstanceTrait;
use Jmhc\Support\Traits\RedisHandlerTrait;

/**
 * 单设备缓存
 * @package Jmhc\Restful\Utils
 */
class SdlCache
{
    use InstanceTrait;
    use RedisHandlerTrait;

    /**
     * @var Connection
     */
    protected $handler;

    /**
     * 场景
     * @var string
     */
    protected $scene = ConstAttributeInterface::DEFAULT_SCENE;

    protected static $cacheKey = 'sdl_%s%d';
    protected static $cacheTmpKey = 'sdl_tmp_%s%d';

    public function __construct()
    {
        $this->handler = $this->getPhpRedisHandler();
    }

    /**
     * 设置场景
     * @param string $scene
     * @return $this
     */
    public function scene(string $scene)
    {
        $this->scene = $scene;
        return $this;
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
            $this->handler->setex($this->getCacheTmpKey($id), config('jmhc-api.sdl_tmp_expire', 10), $oldToken);
        }

        return true;
    }

    /**
     * 删除缓存数据
     * @param int $id
     * @return int
     */
    public function del(int $id)
    {
        return $this->handler->del([$this->getCacheKey($id), $this->getCacheTmpKey($id)]);
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
        // 当前场景
        $scene = $this->scene ? $this->scene . '_' : '';
        return sprintf(static::$cacheKey, $scene, $id);
    }

    /**
     * 获取临时key
     * @param int $id
     * @return string
     */
    protected function getCacheTmpKey(int $id)
    {
        // 当前场景
        $scene = $this->scene ? $this->scene . '_' : '';
        return sprintf(static::$cacheTmpKey, $scene, $id);
    }
}
