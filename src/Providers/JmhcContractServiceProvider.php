<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Providers;

use Illuminate\Support\ServiceProvider;
use Jmhc\Restful\Contracts\AgentInterface;
use Jmhc\Restful\Contracts\RequestParamsInterface;
use Jmhc\Restful\Contracts\UserModelInterface;
use Jmhc\Restful\Contracts\VersionModelInterface;
use Jmhc\Restful\Models\UserModel;
use Jmhc\Restful\Models\VersionModel;
use Jmhc\Restful\Utils\Agent;
use Jmhc\Support\Utils\Collection;

/**
 * 契约服务提供者
 * @package Jmhc\Restful\Providers
 */
class JmhcContractServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 请求参数
        app()->instance(RequestParamsInterface::class, new Collection());
        // 请求 Agent
        app()->instance(AgentInterface::class, Agent::getInstance([
            'request' => request(),
            'userAgent' => request()->header('user-agent', ''),
        ]));

        // 用户模型
        app()->instance(UserModelInterface::class, new UserModel());
        // 版本模型
        app()->instance(VersionModelInterface::class, new VersionModel());
    }
}
