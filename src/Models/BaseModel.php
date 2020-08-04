<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Jmhc\Restful\Contracts\ConstAttributeInterface;
use Jmhc\Restful\Scopes\PrimaryKeyDescScope;
use Jmhc\Restful\Traits\ModelTrait;

/**
 * 基础模型
 * @method ModelTrait initialize()
 * @package Jmhc\Restful\Models
 */
class BaseModel extends Model implements ConstAttributeInterface
{
    use ModelTrait;

    /**
     * 是否使用主键倒序作用域
     * @var bool
     */
    protected static $usePrimaryKeyDescScope = true;

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

        if (static::$usePrimaryKeyDescScope) {
            static::addGlobalScope(PrimaryKeyDescScope::getInstance());
        }
    }

    public function getForeignKey()
    {
        return static::getSnakeSingularName() . '_' . $this->getKeyName();
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->getDateFormat());
    }
}