<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Services;

use Jmhc\Restful\Contracts\ConstAttributeInterface;
use Jmhc\Restful\Contracts\ServiceInterface;
use Jmhc\Restful\Traits\RequestInfoTrait;
use Jmhc\Restful\Traits\ResultThrowTrait;
use Jmhc\Restful\Traits\UserInfoTrait;
use Jmhc\Support\Traits\InstanceTrait;

/**
 * 基础服务
 * @method UserInfoTrait initialize()
 * @package Jmhc\Restful\Services
 */
class BaseService implements ConstAttributeInterface, ServiceInterface
{
    use InstanceTrait;
    use ResultThrowTrait;
    use RequestInfoTrait;
    use UserInfoTrait;

    public function __construct()
    {
        // 设置请求信息
        $this->setRequestInfo();
        $this->initialize();
    }
}