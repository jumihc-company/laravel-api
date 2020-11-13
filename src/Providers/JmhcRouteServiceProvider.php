<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * 路由服务提供者
 * @package Jmhc\Restful\Providers
 */
class JmhcRouteServiceProvider extends RouteServiceProvider
{
    /**
     * 排除的文件
     * @var array
     */
    protected $except = [];

    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        parent::boot();
    }

    public function map()
    {
        $files = glob(base_path('routes/*.php'));
        foreach ($files as $file) {
            // 是否排除
            if ($this->isExcept($file)) {
                continue;
            }

            Route::prefix('')->group($file);
        }
    }

    /**
     * 是否排除
     * @param string $file
     * @return bool
     */
    protected function isExcept(string $file)
    {
        $name = pathinfo($file, PATHINFO_FILENAME);
        return in_array($name, $this->except) || in_array($name . '.php', $this->except);
    }
}
