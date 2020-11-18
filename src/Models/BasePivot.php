<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Jmhc\Database\Contracts\DatabaseInterface;
use Jmhc\Database\Traits\DatabaseTrait;

/**
 * 基础中间模型
 * @method DatabaseTrait initialize()
 * @package Jmhc\Restful\Models
 */
class BasePivot extends Pivot implements DatabaseInterface
{
    use DatabaseTrait;

    protected function initializeBefore()
    {
        // 设置当前表名
        if (empty($this->table)) {
            $this->setTable(static::getSnakeSingularName());
        }
    }
}
