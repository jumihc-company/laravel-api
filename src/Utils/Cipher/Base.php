<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Utils\Cipher;

use Jmhc\Restful\Traits\InstanceTrait;

/**
 * 加密基类
 * @package Jmhc\Restful\Utils\Cipher
 */
abstract class Base
{
    use InstanceTrait;

    /**
     * 配置信息
     * @var array
     */
    protected $config;

    /**
     * 加密方法
     * @var string
     */
    protected $method;

    /**
     * 加密向量
     * @var string
     */
    protected $iv;

    /**
     * 加密key
     * @var string
     */
    protected $key;

    abstract public function encrypt(string $str);

    abstract public function decrypt(string $str);

    public function __construct()
    {
        // 初始化
        $this->initialize();
    }

    /**
     * 初始化
     */
    protected function initialize()
    {
        // 场景
        $scene = strtolower(class_basename(get_called_class()));
        // 配置
        $this->config = config(
            sprintf('jmhc-api.%s', $scene),
            []
        );

        $this->method = $this->config['method'] ?? '';
        $this->iv     = $this->config['iv'] ?? '';
        $this->key    = $this->config['key'] ?? '';
    }
}
