<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

namespace Jmhc\Restful\Providers;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Jmhc\Restful\Utils\LogHelper;

/**
 * 任务服务提供者
 * @package Jmhc\Restful\Providers
 */
class JmhcJobServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 任务失败事件
        Queue::failing(function (JobFailed $event) {
            LogHelper::queue()->save(
                $this->getJobName($event->job) . 'failed',
                $event->exception->getMessage() . PHP_EOL . $event->exception->getTraceAsString()
            );
        });

        // 任务开始事件
        Queue::before(function (JobProcessing $event) {
            LogHelper::queue()->debug(
                $this->getJobName($event->job) . 'handle',
                $event->job->getRawBody()
            );
        });
    }

    /**
     * 获取任务名称
     * @param Job $job
     * @return string
     */
    protected function getJobName(Job $job)
    {
        $payload = $job->payload();
        return ! empty($payload['displayName']) ? class_basename($payload['displayName']). '.' : '';
    }
}
