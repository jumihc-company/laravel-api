<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Traits;

use Jmhc\Restful\Contracts\ServiceInterface;
use Jmhc\Restful\Utils\ParseService;

/**
 * 资源控制器方法
 * @package Jmhc\Restful\Traits
 */
trait ResourceControllerTrait
{
    protected $service;

    public function initialize()
    {
        if (method_exists(__CLASS__, 'initialize')) {
            parent::initialize();
        }
        $this->withService();
    }

    public function index()
    {
        return $this->service->index();
    }

    public function show(int $id)
    {
        if (empty($this->params->id)) {
            $this->params->id = $id;
        }

        return $this->service->show();
    }

    public function store()
    {
        return $this->service->store();
    }

    public function update(string $id = '')
    {
        if (empty($this->params->id)) {
            $this->params->id = $id;
        }

        return $this->service->update();
    }

    public function destroy(string $id = '')
    {
        if (empty($this->params->id)) {
            $this->params->id = $id;
        }

        return $this->service->destroy();
    }

    /**
     * 添加 service 属性
     * @param ServiceInterface|null $service
     * @return $this
     */
    private function withService(ServiceInterface $service = null)
    {
        $this->service = ParseService::run($service ?: $this->service, get_called_class());

        return $this;
    }
}
