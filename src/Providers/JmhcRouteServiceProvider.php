<?php
/**
 * User: YL
 * Date: 2019/10/21
 */

namespace Jmhc\Restful\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class JmhcRouteServiceProvider extends RouteServiceProvider
{
    public function map()
    {
        $files = glob(base_path('routes/*.php'));
        foreach ($files as $file) {
            Route::prefix('')->group($file);
        }
    }
}
