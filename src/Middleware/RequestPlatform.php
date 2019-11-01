<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Restful\PlatformInfo;
use Jmhc\Restful\Utils\Env;

class RequestPlatform
{
    /**
     * 请求平台
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 请求平台
        $requestPlatform = $this->getRequestPlatform(
            $request,
            Env::get('jmhc.request.platform_name', 'request-platform')
        );

        // 所有 user_agent
        $allPlatform = PlatformInfo::getAllPlatform();

        // 平台
        $platform = PlatformInfo::OTHER;
        foreach ($allPlatform as $k => $v) {
            if (preg_match(sprintf('/(%s)/', $k), $requestPlatform)) {
                $platform = $v;
                break;
            }
        }

        // 请求平台
        $request->platform = $platform;

        return $next($request);
    }

    /**
     * 获取请求平台
     * @param Request $request
     * @param string $name
     * @return array|string|null
     */
    protected function getRequestPlatform(Request $request, string $name)
    {
        $platform = $request->header($name, '-');
        if (empty($platform)) {
            $platform = $request->input($name, '-');
        }
        if(empty($platform)) {
            $platform = $request->server('HTTP_USER_AGENT', '-');
        }

        return $platform;
    }
}
