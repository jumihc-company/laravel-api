<?php
/**
 * User: YL
 * Date: 2019/10/21
 */

namespace Jmhc\Restful\Providers;

use Illuminate\Support\ServiceProvider;
use Jmhc\Restful\Contracts\User;
use Jmhc\Restful\Contracts\Version;
use Jmhc\Restful\Models\UserModel;
use Jmhc\Restful\Models\VersionModel;

class JmhcContractServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 用户模型
        app()->instance(User::class, new UserModel());
        // 版本模型
        app()->instance(Version::class, new VersionModel());
    }
}
