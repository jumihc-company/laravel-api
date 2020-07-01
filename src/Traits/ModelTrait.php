<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * 模型辅助
 * @package Jmhc\Restful\Traits
 */
trait ModelTrait
{
    public function __construct(array $attributes = [])
    {
        // 初始前置操作
        $this->initializeBefore();

        // 实例化
        parent::__construct($attributes);

        // 初始操作
        $this->initialize();
    }

    /**
     * 初始化前置操作
     */
    protected function initializeBefore()
    {}

    /**
     * 初始化操作
     */
    protected function initialize()
    {}

    /**
     * 组装参数
     * @param array $params
     * @return Builder
     */
    public static function assemble(array $params)
    {
        /**
         * @var Builder $builder
         */
        $builder = static::query();

        // 组装排序
        static::assembleOrder($builder, $params);
        // 组装limit分页
        static::assembleLimit($builder, $params);
        // 组装page分页
        static::assemblePage($builder, $params);

        return $builder;
    }

    /**
     * 获取蛇形复数名称
     * @return string
     */
    protected static function getSnakePluralName()
    {
        $table = preg_replace(
            '/model$/i', '', class_basename(get_called_class())
        );
        return Str::pluralStudly(Str::snake($table));
    }

    /**
     * 获取蛇形单数名称
     * @return string
     */
    protected static function getSnakeSingularName()
    {
        $table = preg_replace(
            '/model$/i', '', class_basename(get_called_class())
        );
        return Str::singular(Str::snake($table));
    }

    /**
     * 组装排序
     * @param Builder $builder
     * @param array $params
     */
    private static function assembleOrder(Builder &$builder, array $params)
    {
        // 排序字段
        if (! empty($params['sort']) && in_array(
            $params['sort'], $builder->getModel()->getFillable()
            )) {
            $direction = static::DEFAULT_DIRECTION;

            // 排序方式
            if (! empty($params['order']) && in_array($params['order'], ['asc', 'desc'])) {
                $direction = $params['order'];
            }

            // 排序
            $builder->orderBy($params['sort'], $direction);
        }
    }

    /**
     * 组装limit分页
     * @param Builder $builder
     * @param array $params
     */
    private static function assembleLimit(Builder &$builder, array $params)
    {
        if ((! empty($params['limit']) || ! empty($params['offset'])) && empty($params['page']) && empty($params['page_size'])) {
            // 判断参数
            if (empty($params['offset']) || ! is_numeric($params['offset'])) {
                $params['offset'] = static::DEFAULT_OFFSET;
            }
            if (empty($params['limit']) || ! is_numeric($params['limit'])) {
                $params['limit'] = static::DEFAULT_LIMIT;
            }

            // 分页
            $builder->offset(intval($params['offset']))->limit(intval($params['limit']));
        }
    }

    /**
     * 组装page分页
     * @param Builder $builder
     * @param array $params
     */
    private static function assemblePage(Builder &$builder, array $params)
    {
        if (empty($params['limit']) && empty($params['offset']) && (! empty($params['page']) || ! empty($params['page_size']))) {
            // 判断参数
            if (empty($params['page']) || ! is_numeric($params['page'])) {
                $params['page'] = static::DEFAULT_PAGE;
            }
            if (empty($params['page_size']) || ! is_numeric($params['page_size'])) {
                $params['page_size'] = static::DEFAULT_PAGE_SIZE;
            }

            // 分页
            $builder->forPage(intval($params['page']), intval($params['page_size']));
        }
    }
}
