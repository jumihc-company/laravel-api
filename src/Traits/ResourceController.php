<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Traits;

trait ResourceController
{
    protected $service;

    public function index()
    {
        $this->service->index();
    }

    public function show(string $id = '')
    {
        if (empty($this->request->params['id'])) {
            $this->request->params['id'] = $id;
        }

        $this->service->show();
    }

    public function store()
    {
        $this->service->store();
    }

    public function update(string $id = '')
    {
        if (empty($this->request->params['id'])) {
            $this->request->params['id'] = $id;
        }

        $this->service->update();
    }

    public function destroy(string $id = '')
    {
        if (empty($this->request->params['id'])) {
            $this->request->params['id'] = $id;
        }

        $this->service->destroy();
    }
}
