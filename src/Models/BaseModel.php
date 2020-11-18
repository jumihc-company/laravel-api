<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Models;

use Illuminate\Database\Eloquent\Model;
use Jmhc\Database\Contracts\DatabaseInterface;
use Jmhc\Database\Scopes\PrimaryKeyDescScope;
use Jmhc\Database\Traits\DatabaseTrait;

/**
 * 基础模型
 * @method DatabaseTrait initialize()
 * @package Jmhc\Restful\Models
 */
class BaseModel extends Model implements DatabaseInterface
{
    use DatabaseTrait;

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

    /**
     * {@inheritDoc}
     */
    public function getForeignKey()
    {
        return static::getSnakeSingularName() . '_' . $this->getKeyName();
    }
}