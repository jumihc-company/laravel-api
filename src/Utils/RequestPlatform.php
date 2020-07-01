<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Http\Request;
use Jmhc\Restful\Contracts\PlatformInfoInterface;

/**
 * 请求平台
 * @package Jmhc\Restful\Utils
 */
class RequestPlatform
{
    public static function run(Request $request)
    {
        // 请求平台
        $requestPlatform = static::getRequestPlatform(
            $request,
            'request-platform'
        );

        return static::check($requestPlatform);
    }

    /**
     * 检测平台
     * @param string $requestPlatform
     * @return array
     */
    public static function check(string $requestPlatform)
    {
        // 平台
        $platforms[] = PlatformInfoInterface::OTHER;
        foreach (PlatformInfoInterface::KEYWORDS_PLATFORMS as $k => $v) {
            if (preg_match(sprintf('/(%s)/', $k), $requestPlatform, $_match) && ! empty($_match[0])) {
                $platforms[] = $v;
            }
        }

        return $platforms;
    }

    /**
     * 获取请求平台
     * @param Request $request
     * @param string $name
     * @return array|string|null
     */
    protected static function getRequestPlatform(Request $request, string $name)
    {
        $platform = $request->header(ucwords($name, '-'));
        if (empty($platform)) {
            $platform = $request->input($name);
        }
        if(empty($platform)) {
            $platform = $request->server('HTTP_USER_AGENT');
        }

        return $platform;
    }
}