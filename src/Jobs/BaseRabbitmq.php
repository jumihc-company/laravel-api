<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

namespace Jmhc\Restful\Jobs;

use Jmhc\Restful\Utils\Collection;
use Jmhc\Restful\Utils\LogHelper;

/**
 * 基础 rabbitmq 任务
 * @package Jmhc\Restful\Jobs
 */
abstract class BaseRabbitmq extends BaseJob
{
    /**
     * @var Collection
     */
    protected $msg;

    public function __construct(array $msg)
    {
        // 链接名称
        $this->connection = 'rabbitmq';
        // 队列名称
        $this->queue = config('rabbitmq.queue', 'default');
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
