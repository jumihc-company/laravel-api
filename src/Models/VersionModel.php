<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jmhc\Restful\Contracts\Version;

class VersionModel extends BaseModel implements Version
{
    protected function getIsForceAttribute($value)
    {
        return !! $value;
    }

    /**
     * 获取最新版本信息
     * @return Builder|Model|object|null
     */
    public static function getLastInfo()
    {
        return static::query()
            ->first();
    }
}
