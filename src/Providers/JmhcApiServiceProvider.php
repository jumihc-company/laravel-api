<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Providers;

use Illuminate\Support\ServiceProvider;
use Jmhc\Restful\Middleware\CheckSdlMiddleware;
use Jmhc\Restful\Middleware\CheckSignatureMiddleware;
use Jmhc\Restful\Middleware\CheckTokenMiddleware;
use Jmhc\Restful\Middleware\CheckVersionMiddleware;
use Jmhc\Restful\Middleware\ConvertEmptyStringsToNullMiddleware;
use Jmhc\Restful\Middleware\ParamsHandlerMiddleware;
use Jmhc\Restful\Middleware\RequestLockMiddleware;
use Jmhc\Restful\Middleware\RequestLogMiddleware;
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
    protected $routeMiddleware = [
        'jmhc.params.handler' => ParamsHandlerMiddleware::class,
        'jmhc.convert.empty.strings.to.null' => ConvertEmptyStringsToNullMiddleware::class,
        'jmhc.trim.strings' => TrimStringsMiddleware::class,
        'jmhc.request.lock' => RequestLockMiddleware::class,
        'jmhc.request.log' => RequestLogMiddleware::class,
        'jmhc.check.version' => CheckVersionMiddleware::class,
        'jmhc.check.signature' => CheckSignatureMiddleware::class,
        'jmhc.check.token' => CheckTokenMiddleware::class,
        'jmhc.check.sdl' => CheckSdlMiddleware::class,
    ];

    /**
     * @var string
     */
    protected $apiConfigPath;

    /**
     * @var string
     */
    protected $apiDatabaseMigrationsDir;

    public function boot()
    {
        $this->apiConfigPath = __DIR__ . '/../../config/jmhc-api.php';
        $this->apiDatabaseMigrationsDir = __DIR__ . '/../../database/migrations';

        // 注册路由中间件
        $this->registerRouteMiddleware();

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
        // 合并 api 配置
        $this->mergeConfigFrom(
            $this->apiConfigPath,
            'jmhc-api'
        );
    }

    /**
     * 发布文件
     */
    protected function publishFiles()
    {
        // 发布配置文件
        $this->publishes([
            $this->apiConfigPath => config_path('jmhc-api.php'),
        ], 'jmhc-api-config');

        // 发布迁移文件
        $this->publishes([
            $this->apiDatabaseMigrationsDir => database_path('migrations'),
        ], 'jmhc-api-migrations');

        // 发布所有文件
        $this->publishes([
            $this->apiConfigPath => config_path('jmhc-api.php'),
            $this->apiDatabaseMigrationsDir => database_path('migrations'),
        ], 'jmhc-api');
    }
}
