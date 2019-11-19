<?php
/**
 * User: YL
 * Date: 2019/11/18
 */

namespace Jmhc\Restful\Factory;

use Jmhc\Restful\Utils\Helper;
use ReflectionClass;
use ReflectionException;

/**
 * 工厂基类
 * @package Jmhc\Restful\Factory
 */
class BaseFactory
{
    /**
     * 文档注释
     * @var string
     */
    protected static $docComment;

    /**
     * 元数据
     * @var array
     */
    protected static $metadata;

    /**
     * 方法关键字
     * @var string
     */
    protected static $methodKeyword = ' * @method static ';

    public static function __callStatic($name, $arguments)
    {
        // 解析class
        $class = static::parse($name);
        if (empty($class)) {
            return null;
        }

        // 返回实例对象
        return Helper::instance($class, ! empty($arguments[0]), $arguments[1] ?? []);
    }

    /**
     * 获取对应类
     * @param string $method
     * @return mixed|string
     * @throws ReflectionException
     */
    private static function parse(string $method)
    {
        return static::getMetadata(static::getDocComment())[$method] ?? '';
    }

    /**
     * 获取文档注释
     * @return string
     * @throws ReflectionException
     */
    private static function getDocComment()
    {
        if (is_null(static::$docComment)) {
            static::$docComment = (new ReflectionClass(new static()))->getDocComment();
        }

        return static::$docComment;
    }

    /**
     * 获取元数据
     * @param string $docComment
     * @return array
     */
    private static function getMetadata(string $docComment)
    {
        if (is_null(static::$metadata)) {
            static::$metadata = static::createMetadata($docComment);
        }

        return static::$metadata;
    }

    /**
     * 创建元数据
     * @param string $docComment
     * @return array
     */
    private static function createMetadata(string $docComment)
    {
        $metadata = [];
        foreach (explode(PHP_EOL, $docComment) as $meta) {
            if (strpos($meta, static::$methodKeyword) === 0) {
                [$_class, $_method] = static::transform(trim(str_replace(static::$methodKeyword, '', $meta)));
                $metadata[$_method] = $_class;
            }
        }

        return $metadata;
    }

    /**
     * 转换
     * @param string $meta
     * @return array
     */
    private static function transform(string $meta)
    {
        $first = strpos($meta, ' ');
        $last = strpos($meta, '(');
        $start = $first + 1;

        return [
            mb_substr($meta, 0, $first),
            mb_substr($meta, $start, $last - $start)
        ];
    }
}
