<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Traits;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Jmhc\Restful\Contracts\RequestParamsInterface;
use Jmhc\Restful\Utils\Agent;
use Jmhc\Support\Utils\Collection;

/**
 * 请求信息
 * @package Jmhc\Restful\Traits
 */
trait RequestInfoTrait
{
    /**
     * app实例
     * @var Application
     */
    protected $app;

    /**
     * 请求实例
     * @var Request
     */
    protected $request;

    /**
     * 请求参数
     * @var Collection
     */
    protected $params;

    /**
     * 请求ip
     * @var string
     */
    protected $ip;

    /**
     * 请求 Agent
     * @var Agent
     */
    protected $agent;

    /**
     * 设置请求信息
     */
    private function setRequestInfo()
    {
        $this->app = app('app');
        $this->request = request();
        $this->params = app()->get(RequestParamsInterface::class);
        $this->ip = $this->request->ip();
        $this->agent = Agent::getInstance([
            'request' => $this->request,
            'userAgent' => $this->request->header('user-agent', ''),
        ]);
    }
}
