<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Database\Eloquent\Model;

/**
 * 解析 Model
 * @package Jmhc\Restful\Utils
 */
class ParseModel
{
    public static function run($model, string $class)
    {
        if ($model instanceof Model) {
            return $model;
        }

        // 尝试调用
        $call = static::callModel($model);
        if ($call instanceof Model) {
            return $call;
        }

        // 重新组装名字
        $className = class_basename($class);
        $namespace = preg_replace('/' . $className . '$/', '', $class);
        $name = preg_replace(
            '/service$/i', '', $className
        );
        $serviceName = str_replace('Service', 'Model', $namespace) . $name;

        // 不带 Model 后缀
        $call = static::callModel($serviceName);
        if ($call instanceof Model) {
            return $call;
        }

        // 带 Model 后缀
        $call = static::callModel($serviceName . 'Model');
        if ($call instanceof Model) {
            return $call;
        }

        return $model;
    }

    /**
     * 调用 model
     * @param $model
     * @return mixed
     */
    protected static function callModel($model)
    {
        $res = $model;
        if (is_string($model) && class_exists($model)) {
            $res = new $model;
        }

        return $res;
    }
}