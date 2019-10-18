<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Restful\PlatformInfo;

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
        // 请求 user_agent
        $userAgent = $request->server('HTTP_USER_AGENT', '-');

        // 所有 user_agent
        $allUserAgent = PlatformInfo::getAllUserAgent();

        // 平台
        $platform = PlatformInfo::OTHER;
        foreach ($allUserAgent as $k => $v) {
            if (preg_match(sprintf('/(%s)/', $k), $userAgent)) {
                $platform = $v;
                break;
            }
        }

        // 请求平台
        $request->platform = $platform;

        return $next($request);
    }
}
