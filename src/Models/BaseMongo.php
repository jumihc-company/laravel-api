<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

namespace Jmhc\Restful\Models;

use DateTimeInterface;
use Jenssegers\Mongodb\Eloquent\Model;
use Jmhc\Restful\Contracts\ConstAttributeInterface;
use Jmhc\Restful\Scopes\PrimaryKeyDescScope;
use Jmhc\Restful\Traits\ModelTrait;

/**
 * 基础 mongo 模型
 * @method ModelTrait initialize()
 * @package Jmhc\Restful\Models
 */
class BaseMongo extends Model implements ConstAttributeInterface
{
    use ModelTrait;

    /**
     * 关闭属性保护
     * @var bool
     */
    protected static $unguarded = true;

    protected function initializeBefore()
    {
        // 设置链接名称
        if (empty($this->connection)) {
            $this->setConnection('mongodb');
        }

        // 设置表名称
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
        return static::getSnakeSingularName() . '_' . ltrim($this->getKeyName(), '_');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->getDateFormat());
    }
}
