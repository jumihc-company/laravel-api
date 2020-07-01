<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Controllers;

use Illuminate\Routing\Controller;
use Jmhc\Restful\Traits\RequestInfoTrait;
use Jmhc\Restful\Traits\UserInfoTrait;

/**
 * 基础控制器
 * @method UserInfoTrait initialize()
 * @package Jmhc\Restful\Controllers
 */
class BaseController extends Controller
{
    use RequestInfoTrait;
    use UserInfoTrait;

    public function __construct()
    {
        // 设置请求信息
        $this->setRequestInfo();
    }

    public function callAction($method, $parameters)
    {
        $this->initialize();
        return parent::callAction($method, $parameters);
    }
}