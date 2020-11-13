<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Traits;

/**
 * 资源服务钩子方法
 * @package Jmhc\Restful\Traits
 */
trait ResourceServiceHooksTrait
{
    /**
     * index 前置操作
     */
    protected function indexBefore()
    {}

    /**
     * index 后置操作
     */
    protected function indexAfter()
    {}

    /**
     * show 前置操作
     */
    protected function showBefore()
    {}

    /**
     * show 后置操作
     */
    protected function showAfter()
    {}

    /**
     * store 前置操作
     */
    protected function storeBefore()
    {}

    /**
     * store 后置操作
     */
    protected function storeAfter()
    {}

    /**
     * update 前置操作
     */
    protected function updateBefore()
    {}

    /**
     * update 后置操作
     */
    protected function updateAfter()
    {}

    /**
     * destroy 前置操作
     */
    protected function destroyBefore()
    {}

    /**
     * destroy 后置操作
     */
    protected function destroyAfter()
    {}
}