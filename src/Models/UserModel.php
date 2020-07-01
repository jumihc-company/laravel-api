<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jmhc\Restful\Contracts\UserModelInterface;

/**
 * 用户模型
 * @package Jmhc\Restful\Models
 */
class UserModel extends BaseModel implements UserModelInterface
{
    /**
     * 通过id获取信息
     * @param int $id
     * @return Builder|Model|object|null
     */
    public function getInfoById(int $id)
    {
        return static::query()
            ->where('id', $id)
            ->first();
    }
}
