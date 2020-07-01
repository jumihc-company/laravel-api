<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Utils;

use Jmhc\Restful\Contracts\ServiceInterface;

/**
 * 解析 Service
 * @package Jmhc\Restful\Utils
 */
class ParseService
{
    /**
     * 运行解析
     * @param $service
     * @param string $class
     * @return ServiceInterface|mixed
     */
    public static function run($service, string $class)
    {
        if ($service instanceof ServiceInterface) {
            return $service;
        }

        // 尝试调用
        $call = static::callService($service);
        if ($call instanceof ServiceInterface) {
            return $call;
        }

        // 重新组装名字
        $className = class_basename($class);
        $namespace = preg_replace('/' . $className . '$/', '', $class);
        $name = preg_replace(
            '/controller$/i', '', $className
        );
        $serviceName = str_replace('Controller', 'Service', $namespace) . $name;

        // 不带 Service 后缀
        $call = static::callService($serviceName);
        if ($call instanceof ServiceInterface) {
            return $call;
        }

        // 带 Service 后缀
        $call = static::callService($serviceName . 'Service');
        if ($call instanceof ServiceInterface) {
            return $call;
        }

        return $service;
    }

    /**
     * 调用 service
     * @param $service
     * @return mixed
     */
    protected static function callService($service)
    {
        $res = $service;
        if (is_string($service) && class_exists($service)) {
            if (method_exists($service, 'getInstance')) {
                $res = call_user_func([$service, 'getInstance']);
            } else {
                $res = new $service;
            }
        }

        return $res;
    }
}