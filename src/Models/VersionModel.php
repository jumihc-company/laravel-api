<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jmhc\Restful\Contracts\VersionModelInterface;

/**
 * 版本模型
 * @package Jmhc\Restful\Models
 */
class VersionModel extends BaseModel implements VersionModelInterface
{
    protected function getIsForceAttribute($value)
    {
        return !! $value;
    }

    /**
     * 获取最新版本信息
     * @return Builder|Model|object|null
     */
    public function getLastInfo()
    {
        return static::query()
            ->first();
    }
}
