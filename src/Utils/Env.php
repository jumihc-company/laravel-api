<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Utils;

class Env
{
    /**
     * 获取环境变量
     * @param string $key
     * @param null $default
     * @return array|mixed|null
     */
    public static function get(string $key, $default = null)
    {
        $key = str_replace('.', '_', strtoupper($key));

        if (isset($_ENV[$key])) {
            return static::adapter($_ENV[$key]);
        }

        $res = [];
        foreach ($_ENV as $k => $v) {
            if (stripos($k, $key) !== false) {
                $_key = trim(strtolower(str_replace($key, '', $k)), '_');
                $res[$_key] = static::adapter($v);
            }
        }

        if (! empty($res)) {
            if (count($res) == 1) {
                return current($res);
            }

            return $res;
        }

        return $default;
    }

    /**
     * 返回适配
     * @param $value
     * @return mixed
     */
    protected static function adapter($value)
    {
        if (is_array($value)) {
            return $value;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (preg_match('/\A([\'"])(.*)\1\z/', $value, $matches)) {
            return $matches[2];
        }

        return $value;
    }
}
