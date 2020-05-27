<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Support\Arr;

/**
 * 辅助
 * @package Jmhc\Restful\Utils
 */
class Helper
{
    /**
     * 获取url地址
     * @param $url
     * @param $value
     * @return string
     */
    protected static function getUrl($url, $value)
    {
        if (empty($value)) {
            return '';
        } elseif (preg_match('/^(http|https)/', $value)) {
            return $value;
        }

        return preg_replace('/\/*$/', '', $url) . '/' . preg_replace('/^\/*/', '', $value);
    }

    /**
     * 获取源路径
     * @param $url
     * @param $value
     * @return string
     */
    protected static function getOriginPath($url, $value)
    {
        return str_replace($url, '', $value);
    }

    /**
     * 转换成布尔值
     * @param $value
     * @return bool
     */
    public static function boolean($value)
    {
        if (is_bool($value)) {
            return $value;
        } elseif ($value === 'true') {
            return true;
        } elseif ($value === 'false') {
            return false;
        }

        return !! $value;
    }

    /**
     * 数值转金钱
     * @param $value
     * @return float
     */
    public static function int2money($value)
    {
        return round($value / 100, 2);
    }

    /**
     * 金钱转数值
     * @param $value
     * @return int
     */
    public static function money2int($value)
    {
        return intval($value * 100);
    }

    /**
     * 数组转换成key
     * @param array $arr
     * @param string $flag
     * @return string
     */
    public static function array2key(array $arr, string $flag = '')
    {
        return md5(json_encode(Arr::sortRecursive($arr)) . $flag);
    }

    /**
     * 单例辅助
     * @param string $class
     * @param bool $refresh
     * @param array $params
     * @return mixed
     */
    public static function instance(string $class, bool $refresh = false, array $params = [])
    {
        $id = static::array2key($params, $class);
        if (! app()->has($id) || $refresh) {
            app()->instance($id, app()->make($class, $params));
        }

        return app()->get($id);
    }

    /**
     * 获取测试环境变量
     * @param string $test
     * @param string $product
     * @param null $default
     * @return mixed
     */
    public static function getTestEnv(string $test, string $product, $default = null)
    {
        $res = env($test);

        if (! $res) {
            $res = env($product, $default);
        }

        return $res;
    }
}
