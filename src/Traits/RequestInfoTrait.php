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
     * @var Application
     */
    protected $app;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Collection
     */
    protected $params;

    /**
     * @var string
     */
    protected $ip;

    /**
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
