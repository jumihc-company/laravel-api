<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Jmhc\Restful\Utils\Helper;

/**
 * 单例类 trait
 * @package Jmhc\Restful\Traits
 */
trait InstanceTrait
{
    /**
     * getInstance
     * @param array $params
     * @return static
     * @throws BindingResolutionException
     */
    public static function getInstance(array $params = [])
    {
        $id = Helper::array2key($params, get_called_class());
        if (! app()->has($id)) {
            app()->instance($id, app()->make(get_called_class(), $params));
        }

        return app()->get($id);
    }
}
