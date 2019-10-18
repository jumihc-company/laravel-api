<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Controllers;

use Illuminate\Routing\Controller;
use Jmhc\Restful\Traits\RequestInfoTrait;
use Jmhc\Restful\Traits\ResourceController;
use Jmhc\Restful\Traits\UserInfoTrait;

/**
 * @method UserInfoTrait initialize()
 */
class BaseController extends Controller
{
    use ResourceController;
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