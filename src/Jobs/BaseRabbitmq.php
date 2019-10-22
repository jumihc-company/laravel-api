<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

namespace Jmhc\Restful\Jobs;

use Jmhc\Restful\Utils\Collection;
use Jmhc\Restful\Utils\Env;
use Jmhc\Restful\Utils\LogHelper;

abstract class BaseRabbitmq extends BaseJob
{
    /**
     * @var Collection
     */
    protected $msg;

    public function __construct(array $msg)
    {
        // 链接名称
        $this->connection = Env::get('rabbitmq.connection', 'rabbitmq');
        // 队列名称
        $this->queue = Env::get('rabbitmq.queue', 'default');
        // 消息
        $this->msg = new Collection($msg);

        LogHelper::queue()
            ->debug(
                $this->getClassBaseName('.send'),
                $this->msg->toJson(JSON_UNESCAPED_UNICODE)
            );
    }

    /**
     * 获取类名称
     * @param string $name
     * @return string
     */
    public function getClassBaseName(string $name = '')
    {
        return class_basename(get_called_class()) . $name;
    }
}
