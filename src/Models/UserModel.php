<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jmhc\Restful\Contracts\User;

class UserModel extends BaseModel implements User
{
    /**
     * 通过id获取信息
     * @param int $id
     * @return Builder|Model|object|null
     */
    public static function getInfoById(int $id)
    {
        return static::query()
            ->where('id', $id)
            ->first();
    }
}
