<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Services;

use Jmhc\Restful\Contracts\ConstAttribute;
use Jmhc\Restful\Traits\Instance;
use Jmhc\Restful\Traits\RedisHandler;
use Jmhc\Restful\Traits\RequestInfoTrait;
use Jmhc\Restful\Traits\ResourceService;
use Jmhc\Restful\Traits\ResultThrow;
use Jmhc\Restful\Traits\UserInfoTrait;

/**
 * @method UserInfoTrait initialize()
 */
class BaseService implements ConstAttribute
{
    use Instance;
    use ResultThrow;
    use RedisHandler;
    use ResourceService;
    use RequestInfoTrait;
    use UserInfoTrait;

    public function __construct()
    {
        // 设置请求信息
        $this->setRequestInfo();
        $this->initialize();
    }
}