<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Jmhc\Restful\Contracts\ConstAttribute;
use Jmhc\Restful\Traits\ModelTrait;

/**
 * @method ModelTrait initialize()
 */
class BasePivot extends Pivot implements ConstAttribute
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
