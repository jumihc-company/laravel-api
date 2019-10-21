<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Traits;

use Illuminate\Database\Eloquent\Model;
use Jmhc\Restful\Utils\Collection;

trait UserInfoTrait
{
    /**
     * 登录用户id
     * @var int
     */
    protected $userId = 0;

    /**
     * 登录用户信息
     * @var Collection|Model
     */
    protected $userInfo;

    /**
     * 初始化操作
     */
    protected function initialize()
    {
        $this->userInfo = $this->request->userInfo ?? new Collection();
        $this->userId = $this->userInfo->id ?? 0;
    }
}
