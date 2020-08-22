<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Traits;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Jmhc\Restful\Contracts\ConstAttributeInterface;
use Jmhc\Restful\Utils\PaginateHelper;
use Jmhc\Restful\Utils\ParseModel;
use Jmhc\Support\Utils\DbHelper;

/**
 * 资源服务方法
 * @package Jmhc\Restful\Traits
 */
trait ResourceServiceTrait
{
    use BuilderAssembleTrait;

    /**
     * 当前模型
     * @var string
     */
    protected $model = '';

    /**
     * 标题
     * @var string
     */
    protected $title = '';

    /**
     * index 查询字段
     * @var array
     */
    protected $indexColumns = ['*'];

    /**
     * show 查询字段
     * @var array
     */
    protected $showColumns = ['*'];

    /**
     * 添加字段
     * @var array
     * ['column', ['column' => 'column', 'default' => '', 'custom' => '']]
     */
    protected $storeColumns = ['*'];

    /**
     * 更新字段
     * @var array
     * ['column', ['column' => 'column', 'default' => '', 'custom' => '']]
     */
    protected $updateColumns = [];

    /**
     * 是否返回 index 页码相关字段
     * @var bool
     */
    protected $isResultIndexPages = false;

    /**
     * index 返回页码相关字段
     * @var array
     */
    protected $indexResultPagesColumns = ['current_page', 'data', 'per_page', 'total'];

    public function index()
    {
        // 操作是否返回 index 页码相关字段
        $this->handlerIsResultIndexPages();

        // 查询前操作
        $this->indexBeforeHandler();

        // 查询构造器
        $builder = $this->withModel();

        // 执行构造查询构造器函数
        $callback = $this->withIndexBuilder();
        if ($callback instanceof Closure) {
            $callback($builder);
        }

        // 获取填充字段
        $columns = $this->getFillable($builder, $this->indexColumns);

        // 参数
        $params = $this->params->toArray();

        // 组装排序
        static::assembleOrder($builder, $params, $columns);

        // 组装搜索条件
        $this->indexSearch($builder);

        // 查询
        $list = $this->indexSelectList($builder, $params, $this->indexColumns);
        if ($list->isEmpty()) {
            $this->noData();
        }

        // 执行返回数据函数
        $callback = $this->withIndexResult();
        if ($callback instanceof Closure) {
            $callback($this->indexSelectResultList($list));
        }

        // 查询后操作
        $this->indexAfterHandler();

        $this->success($this->indexSuccessList($list, $this->indexResultPagesColumns));
    }

    public function show()
    {
        // 查询前操作
        $this->showBeforeHandler();

        // 查询构造器
        $builder = $this->withModel();

        // 执行构造查询构造器函数
        $callback = $this->withShowBuilder();
        if ($callback instanceof Closure) {
            $callback($builder);
        }

        // 默认通过主键查询
        $builder->where($builder->getModel()->getKeyName(), $this->params->id);

        // 查询
        $info = $builder
            ->firstOr($this->showColumns, function () {
                $this->noData();
            });

        // 执行返回数据函数
        $callback = $this->withShowResult();
        if ($callback instanceof Closure) {
            $callback($info);
        }

        // 查询后操作
        $this->showAfterHandler();

        $this->success($info);
    }

    public function store()
    {
        // 添加前操作
        $this->storeBeforeHandler();

        // 执行操作
        $callback = $this->withStoreHandler();
        if ($callback instanceof Closure) {
            $callback();
        } else {
            // 查询构造器
            $builder = $this->withModel();

            // 操作保存数据
            $columns = $this->getFillable($builder, $this->storeColumns);
            $data = $this->handlerSaveData($columns);
            if (empty($data)) {
                $this->error('保存数据不存在');
            }

            // 保存
            $model = $builder
                ->create($data);
            // 当前这条数据id
            $this->params->id = $model->id;
        }

        // 添加后操作
        $this->storeAfterHandler();

        $this->success();
    }

    public function update()
    {
        // 更新前操作
        $this->updateBeforeHandler();

        // 执行操作
        $callback = $this->withUpdateHandler();
        if ($callback instanceof Closure) {
            $callback();
        } else {
            // 字段数据
            $columns = $this->updateColumns;
            if (empty($columns)) {
                $columns = $this->storeColumns;
            }

            // 查询构造器
            $builder = $this->withModel();

            // 操作保存数据
            $columns = $this->getFillable($builder, $columns);
            $data = $this->handlerSaveData($columns);
            if (empty($data) || ! $this->params->id) {
                $this->error('更新数据不存在');
            }

            // 更新
            $info = $builder
                ->where($builder->getModel()->getKeyName(), $this->params->id)
                ->first(['id']);
            foreach ($data as $k => $v) {
                $info->{$k} = $v;
            }
            $info->save();
        }

        // 更新后操作
        $this->updateAfterHandler();

        $this->success();
    }

    public function destroy()
    {
        // 简单验证id
        $ids = explode(',', $this->params->id);
        if (empty($ids)) {
            $this->error(sprintf('删除%s不存在', $this->title));
        }

        // 删除前操作
        $callback = $this->withDestroyBeforeHandler();
        if ($callback instanceof Closure) {
            $callback($ids);
        }

        // 执行操作
        $callback = $this->withDestroyHandler();
        if ($callback instanceof Closure) {
            $callback($ids);
        } else {
            // 查询构造器
            $builder = $this->withModel();

            // 删除
            $builder
                ->whereIn($builder->getModel()->getKeyName(), $ids)
                ->delete();
        }

        // 删除后操作
        $this->destroyAfterHandler();

        $this->success();
    }

    /**
     * 设置操作模型
     * @return Builder
     */
    protected function withModel() : Builder
    {
        $model = ParseModel::run($this->model, get_called_class());
        if (! method_exists($this->model, 'newQuery')) {
            $this->error('属性 model 不是有效的模型类');
        }

        return $model->newQuery();
    }

    /**
     * index 查询前操作
     */
    protected function indexBeforeHandler()
    {}

    /**
     * 设置 index 查询构造器
     * function($builder) {}
     */
    protected function withIndexBuilder()
    {}

    /**
     * index 搜索条件
     * @param Builder $builder
     */
    protected function indexSearch(Builder $builder)
    {}

    /**
     * 设置 index 返回数据
     * function(Collection $list) {}
     */
    protected function withIndexResult()
    {}

    /**
     * index 查询后操作
     */
    protected function indexAfterHandler()
    {}

    /**
     * show 查询前操作
     */
    protected function showBeforeHandler()
    {}

    /**
     * 设置 show 查询构造器
     * function($builder) {}
     */
    protected function withShowBuilder()
    {}

    /**
     * 设置 show 返回数据
     * function(Model $info) {}
     */
    protected function withShowResult()
    {}

    /**
     * show 查询后操作
     */
    protected function showAfterHandler()
    {}

    /**
     * 添加前操作
     */
    protected function storeBeforeHandler()
    {}

    /**
     * 设置 store 操作
     * function() {}
     */
    protected function withStoreHandler()
    {}

    /**
     * 添加后操作
     */
    protected function storeAfterHandler()
    {}

    /**
     * 更新前操作
     */
    protected function updateBeforeHandler()
    {}

    /**
     * 设置 update 操作
     * function() {}
     */
    protected function withUpdateHandler()
    {}

    /**
     * 更新后操作
     */
    protected function updateAfterHandler()
    {}

    /**
     * 删除前操作
     * function(array $ids) {}
     */
    protected function withDestroyBeforeHandler()
    {}

    /**
     * 设置 destroy 操作
     * function(array $ids) {}
     */
    protected function withDestroyHandler()
    {}

    /**
     * 删除后操作
     */
    protected function destroyAfterHandler()
    {}

    /**
     * 操作是否返回 index 页码相关字段
     */
    protected function handlerIsResultIndexPages()
    {
        // 请求设置的
        if (! is_null($this->params->is_result_index_pages)) {
            $this->isResultIndexPages = !! $this->params->is_result_index_pages;
        }
    }

    /**
     * index 查询列表
     * @param Builder $builder
     * @param array $params
     * @param array $columns
     * @return LengthAwarePaginator|Builder[]|Collection
     */
    protected function indexSelectList(Builder $builder, array $params, array $columns)
    {
        // 如果需要返回分页参数
        if ($this->isResultIndexPages) {
            // 分页参数
            $page = $this->params->page ?: ConstAttributeInterface::DEFAULT_PAGE;
            $pageSize = $this->params->page_size ?: ConstAttributeInterface::DEFAULT_PAGE_SIZE;
            return $builder
                ->paginate($pageSize, $columns, 'page', $page);
        }

        // 组装limit分页
        static::assembleLimit($builder, $params);
        // 组装page分页
        static::assemblePage($builder, $params);
        return $builder
            ->select($columns)
            ->get();
    }

    /**
     * index 查询结果列表
     * @param LengthAwarePaginator|Builder[]|Collection $list
     * @return mixed
     */
    protected function indexSelectResultList($list)
    {
        return $this->isResultIndexPages ? $list->getCollection() : $list;
    }

    /**
     * index 执行成功返回列表
     * @param $list
     * @param array $indexResultPagesColumns
     * @return array|mixed
     */
    protected function indexSuccessList($list, array $indexResultPagesColumns)
    {
        return $this->isResultIndexPages ? PaginateHelper::paginate($list, $indexResultPagesColumns) : $list;
    }

    /**
     * 获取填充字段
     * @param Builder $builder
     * @param array $columns
     * @return array
     */
    private function getFillable(Builder $builder, array $columns)
    {
        $res = $columns;

        if (in_array('*', $columns)) {
            $res = array_column(DbHelper::getInstance()->getAllColumns($builder->getModel()->getTable()), 'column_name');
        }

        return $res;
    }

    /**
     * 操作保存数据
     * @param array $columns
     * @return array
     */
    private function handlerSaveData(array $columns)
    {
        $res = [];
        foreach ($columns as $v) {
            // 字段名称
            if (! is_array($v)) {
                $res[$v] = $this->params->{$v};
                continue;
            }

            // 存在默认值的字段
            if (isset($v['column']) && isset($v['default'])) {
                $res[$v['column']] = $this->params->{$v['column']} ?: $v['default'];
                continue;
            }

            // 存在自定义的字段
            if (isset($v['column']) && isset($v['custom'])) {
                $res[$v['column']] = $v['custom'];
            }
        }
        return $res;
    }
}
