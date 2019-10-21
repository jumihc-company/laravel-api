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
        $this->service->updateRequestInfo()->index();
    }

    public function show(string $id = '')
    {
        if (empty($this->request->params['id'])) {
            $this->request->params['id'] = $id;
        }

        $this->service->updateRequestInfo()->show();
    }

    public function store()
    {
        $this->service->updateRequestInfo()->store();
    }

    public function update(string $id = '')
    {
        if (empty($this->request->params['id'])) {
            $this->request->params['id'] = $id;
        }

        $this->service->updateRequestInfo()->update();
    }

    public function destroy(string $id = '')
    {
        if (empty($this->request->params['id'])) {
            $this->request->params['id'] = $id;
        }

        $this->service->updateRequestInfo()->destroy();
    }
}
