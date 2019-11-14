<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Providers;

use Illuminate\Support\ServiceProvider;
use Jmhc\Restful\Console\Commands\MakeCommonModelCommand;
use Jmhc\Restful\Console\Commands\MakeControllerCommand;
use Jmhc\Restful\Console\Commands\MakeModelCommand;
use Jmhc\Restful\Console\Commands\MakeServiceCommand;
use Jmhc\Restful\Middleware\CheckSdlMiddleware;
use Jmhc\Restful\Middleware\CheckSignatureMiddleware;
use Jmhc\Restful\Middleware\CheckTokenMiddleware;
use Jmhc\Restful\Middleware\CheckVersionMiddleware;
use Jmhc\Restful\Middleware\ConvertEmptyStringsToNullMiddleware;
use Jmhc\Restful\Middleware\CorsMiddleware;
use Jmhc\Restful\Middleware\ParamsHandlerMiddleware;
use Jmhc\Restful\Middleware\RequestLockMiddleware;
use Jmhc\Restful\Middleware\RequestLogMiddleware;
use Jmhc\Restful\Middleware\RequestPlatformMiddleware;
use Jmhc\Restful\Middleware\TrimStringsMiddleware;

/**
 * Api 服务提供者
 * @package Jmhc\Restful\Providers
 */
class JmhcApiServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        MakeCommonModelCommand::class,
        MakeControllerCommand::class,
        MakeServiceCommand::class,
        MakeModelCommand::class,
    ];

    /**
     * @var array
     */
    protected $routeMiddleware = [
        'jmhc.cors' => CorsMiddleware::class,
        'jmhc.params.handler' => ParamsHandlerMiddleware::class,
        'jmhc.convert.empty.strings.to.null' => ConvertEmptyStringsToNullMiddleware::class,
        'jmhc.trim.strings' => TrimStringsMiddleware::class,
        'jmhc.request.lock' => RequestLockMiddleware::class,
        'jmhc.request.log' => RequestLogMiddleware::class,
        'jmhc.request.platform' => RequestPlatformMiddleware::class,
        'jmhc.check.version' => CheckVersionMiddleware::class,
        'jmhc.check.signature' => CheckSignatureMiddleware::class,
        'jmhc.check.token' => CheckTokenMiddleware::class,
        'jmhc.check.sdl' => CheckSdlMiddleware::class,
    ];

    public function boot()
    {
        // 注册路由中间件
        $this->registerRouteMiddleware();

        // 注册命令
        $this->commands($this->commands);

        // 合并配置
        $this->mergeConfig();

        // 发布文件
        $this->publishFiles();
    }

    /**
     * 注册路由中间件
     */
    protected function registerRouteMiddleware()
    {
        // 注册路由
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
    }

    /**
     * 合并配置
     */
    protected function mergeConfig()
    {
        // 合并api配置
        $this->mergeConfigFrom(
            jmhc_api_config_path('jmhc-api.php'),
            'jmhc-api'
        );

        // 合并mongodb配置
        $this->mergeConfigFrom(
            jmhc_api_config_path('jmhc-mongodb.php'),
            'database.connections.mongodb'
        );
    }

    /**
     * 发布文件
     */
    protected function publishFiles()
    {
        // 发布配置文件
        $this->publishes([
            jmhc_api_config_path('jmhc-api.php') => config_path('jmhc-api.php'),
        ], 'jmhc-api-config');

        // 发布迁移文件
        $this->publishes([
            jmhc_api_database_path('migrations') => database_path('migrations'),
        ], 'jmhc-api-migrations');

        // 发布资源文件
        $this->publishes([
            jmhc_api_resource_path('lang') => resource_path('lang'),
        ], 'jmhc-api-resources');

        // 发布所有文件
        $this->publishes([
            jmhc_api_config_path('jmhc-api.php') => config_path('jmhc-api.php'),
            jmhc_api_database_path('migrations') => database_path('migrations'),
            jmhc_api_resource_path('lang') => resource_path('lang'),
        ], 'jmhc-api');
    }
}
