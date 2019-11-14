<?php
/**
 * User: YL
 * Date: 2019/10/21
 */

namespace Jmhc\Restful\Providers;

use Illuminate\Support\ServiceProvider;
use Jmhc\Restful\Contracts\UserModelInterface;
use Jmhc\Restful\Contracts\VersionModelInterface;
use Jmhc\Restful\Models\UserModel;
use Jmhc\Restful\Models\VersionModel;

/**
 * 契约服务提供者
 * @package Jmhc\Restful\Providers
 */
class JmhcContractServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 用户模型
        app()->instance(UserModelInterface::class, new UserModel());
        // 版本模型
        app()->instance(VersionModelInterface::class, new VersionModel());
    }
}
