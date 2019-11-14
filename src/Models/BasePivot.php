<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Jmhc\Restful\Contracts\ConstAttributeInterface;
use Jmhc\Restful\Traits\ModelTrait;

/**
 * 基础中间模型
 * @method ModelTrait initialize()
 * @package Jmhc\Restful\Models
 */
class BasePivot extends Pivot implements ConstAttributeInterface
{
    use ModelTrait;

    protected function initializeBefore()
    {
        // 设置当前表名
        if (empty($this->table)) {
            $this->setTable(static::getSnakeSingularName());
        }
    }
}
