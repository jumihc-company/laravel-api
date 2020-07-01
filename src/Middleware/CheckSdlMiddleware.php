<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Restful\Contracts\ResultCodeInterface;
use Jmhc\Restful\Contracts\ResultMsgInterface;
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
        $token = Token::get();
        // token和用户id存在
        if (! empty($token) && ! empty($request->userInfo->id)) {
            if (! SdlCache::getInstance()->verify($request->userInfo->id, $token)) {
                $this->error(ResultMsgInterface::SDL, ResultCodeInterface::SDL);
            }
        }

        return $next($request);
    }
}
