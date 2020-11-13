<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Traits;

use Jmhc\Support\Utils\RequestInfo;

/**
 * 资源服务分页方法
 * @package Jmhc\Restful\Traits
 */
trait ResourceServicePageTrait
{
    /**
     * 是否启用分页
     * @var bool
     */
    private $isEnablePage = true;

    /**
     * 是否返回分页字段
     * @var bool
     */
    private $isResultPageField = false;

    /**
     * 返回页码相关字段
     * @var array
     */
    private $resultPageFields = ['current_page', 'data', 'page_size', 'total'];

    /**
     * 是否分页
     * @return bool
     */
    private function isPage()
    {
        // 是否启用分页并返回分页字段
        return $this->isEnablePage && $this->isResultPageField;
    }

    /**
     * 处理是否启用分页
     */
    private function handleIsEnablePage()
    {
        $this->handleBoolParam('isEnablePage', 'is_enable_page');
    }

    /**
     * 处理是否返回分页字段
     */
    private function handlerIsResultPageField()
    {
        $this->handleBoolParam('isResultPageField', 'is_result_page_field');
    }

    /**
     * 处理布尔参数
     * @param string $prop
     * @param string $name
     */
    private function handleBoolParam(string $prop, string $name)
    {
        $param = RequestInfo::getParam(request(), $name, null, true);
        if (is_null($param)) {
            return;
        }

        $this->{$prop} = !! $param;
    }
}