<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Traits;

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
     */
    public static function getInstance(array $params = [])
    {
        return Helper::instance(get_called_class(), false, $params);
    }
}
