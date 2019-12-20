<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Services;

use Jmhc\Restful\Contracts\ConstAttributeInterface;
use Jmhc\Restful\Traits\InstanceTrait;
use Jmhc\Restful\Traits\RedisHandlerTrait;
use Jmhc\Restful\Traits\RequestInfoTrait;
use Jmhc\Restful\Traits\ResultThrowTrait;
use Jmhc\Restful\Traits\UserInfoTrait;

/**
 * 基础服务
 * @method UserInfoTrait initialize()
 * @package Jmhc\Restful\Services
 */
class BaseService implements ConstAttributeInterface
{
    use InstanceTrait;
    use ResultThrowTrait;
    use RedisHandlerTrait;
    use RequestInfoTrait;
    use UserInfoTrait;

    public function __construct()
    {
        // 设置请求信息
        $this->setRequestInfo();
        $this->initialize();
    }

    /**
     * 更新属性
     * @return $this
     */
    public function updateAttribute()
    {
        // 设置请求信息
        $this->setRequestInfo();

        return $this;
    }
}