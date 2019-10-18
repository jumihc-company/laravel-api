<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Utils\Cipher;

abstract class Base
{
    protected static $instance;

    // 传入配置
    protected $config;

    // 加密方法
    protected $method;
    // iv
    protected $iv;
    // 加密key
    protected $key;

    abstract public function encrypt(string $str);

    abstract public function decrypt(string $str);

    private function __construct(array $config)
    {
        $this->config = $config;

        // 初始化
        $this->initialize();
    }

    /**
     * getInstance
     * @param array $config
     * @return static
     */
    public static function getInstance(array $config)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($config);
        }

        return static::$instance;
    }

    /**
     * 初始化
     */
    protected function initialize()
    {
        $this->method = $this->config['method'];
        $this->iv     = $this->config['iv'];
        $this->key    = $this->config['key'];
    }
}
