<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Models;

use Illuminate\Database\Eloquent\Model;
use Jmhc\Restful\Contracts\ConstAttribute;
use Jmhc\Restful\Scopes\PrimaryKeyDescScope;
use Jmhc\Restful\Traits\ModelTrait;

/**
 * @method ModelTrait initialize()
 */
class BaseModel extends Model implements ConstAttribute
{
    use ModelTrait;

    protected function initializeBefore()
    {
        // 设置当前表名
        if (empty($this->table)) {
            $this->setTable(static::getSnakePluralName());
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(PrimaryKeyDescScope::getInstance());
    }

    public function getForeignKey()
    {
        return static::getSnakeSingularName() . '_' . $this->getKeyName();
    }
}