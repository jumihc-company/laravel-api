<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Providers;

use Illuminate\Support\ServiceProvider;
use Jmhc\Restful\Console\Commands\MakeCommonModel;
use Jmhc\Restful\Middleware\AllowCrossDomain;
use Jmhc\Restful\Middleware\CheckSdl;
use Jmhc\Restful\Middleware\CheckSignature;
use Jmhc\Restful\Middleware\CheckToken;
use Jmhc\Restful\Middleware\CheckVersion;
use Jmhc\Restful\Middleware\ConvertEmptyStringsToNull;
use Jmhc\Restful\Middleware\ParamsHandler;
use Jmhc\Restful\Middleware\RequestLog;
use Jmhc\Restful\Middleware\RequestPlatform;
use Jmhc\Restful\Middleware\TrimStrings;
use Jmhc\Restful\Utils\Env;

class JmhcApiServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        MakeCommonModel::class,
    ];

    /**
     * @var array
     */
    protected $routeMiddleware = [
        'jmhc.allow.cross' => AllowCrossDomain::class,
        'jmhc.params.handler' => ParamsHandler::class,
        'jmhc.convert.empty.strings.to.null' => ConvertEmptyStringsToNull::class,
        'jmhc.trim.strings' => TrimStrings::class,
        'jmhc.request.log' => RequestLog::class,
        'jmhc.request.platform' => RequestPlatform::class,
        'jmhc.check.version' => CheckVersion::class,
        'jmhc.check.signature' => CheckSignature::class,
        'jmhc.check.token' => CheckToken::class,
        'jmhc.check.sdl' => CheckSdl::class,
    ];

    public function boot()
    {
        // 注册路由
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
        // 合并mongodb配置
        $this->mergeConfigFrom(
            jmhc_api_config_path('jmhc-mongodb.php'),
            sprintf(
                'database.connections.%s',
                Env::get('jmhc.mongodb.connection', 'mongodb')
            )
        );

        // 合并rabbitmq配置
        $this->mergeConfigFrom(
            jmhc_api_config_path('jmhc-rabbitmq.php'),
            sprintf(
                'queue.connections.%s',
                Env::get('jmhc.rabbitmq.connection', 'rabbitmq')
            )
        );
    }

    /**
     * 发布文件
     */
    protected function publishFiles()
    {
        // 发布配置文件
        $this->publishes([
            jmhc_api_config_path('jmhc-cross.php') => config_path('jmhc-cross.php'),
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
            jmhc_api_config_path('jmhc-cross.php') => config_path('jmhc-cross.php'),
            jmhc_api_database_path('migrations') => database_path('migrations'),
            jmhc_api_resource_path('lang') => resource_path('lang'),
        ], 'jmhc-api');
    }
}
