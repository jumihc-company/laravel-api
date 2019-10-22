<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

namespace Jmhc\Restful\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jmhc\Restful\Contracts\ConstAttribute;
use Jmhc\Restful\Traits\ModelTrait;
use Jmhc\Restful\Utils\Env;

class BaseMongo extends Model implements ConstAttribute
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
            $this->setConnection(
                Env::get('mongodb.connection','mongodb')
            );
        }

        // 设置表名称
        if (empty($this->table)) {
            $this->setTable(static::getSnakePluralName());
        }
    }
}
