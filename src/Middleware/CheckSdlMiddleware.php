<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Restful\Contracts\ResultCodeInterface;
use Jmhc\Restful\Traits\ResultThrowTrait;
use Jmhc\Restful\Utils\SdlCache;
use Jmhc\Restful\Utils\Token;

/**
 * 检测单设备登录中间件
 * @package Jmhc\Restful\Middleware
 */
class CheckSdlMiddleware
{
    use ResultThrowTrait;

    public function handle(Request $request, Closure $next)
    {
        // 令牌
        $token = Token::get();

        // 令牌或用户id不存在
        if (empty($token) || empty($request->userInfo->id)) {
            return $next($request);
        }

        // [加密字符, 加密时间, 加密场景]
        [, , $scene] = Token::parse($token);

        // 缓存
        $cache = SdlCache::getInstance();

        // 场景存在
        if ($scene) {
            $cache->scene($scene);
        }

        // 验证缓存
        if (! $cache->verify($request->userInfo->id, $token)) {
            $this->error(jmhc_api_lang_messages_trans('sdl'), ResultCodeInterface::SDL);
        }

        return $next($request);
    }
}
