<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Traits;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Jmhc\Restful\PlatformInfo;
use Jmhc\Restful\Utils\Collection;

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
     * 请求平台 PlatformInfo::other
     * @var string
     */
    protected $platform;

    /**
     * 设置请求信息
     */
    private function setRequestInfo()
    {
        $this->app = app('app');
        $this->request = request();
        $this->params = new Collection($this->request->params);
        $this->ip = $this->request->ip();
        $this->platform = $this->request->platform ?? PlatformInfo::OTHER;
    }
}
