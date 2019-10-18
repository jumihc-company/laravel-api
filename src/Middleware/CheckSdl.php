<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Restful\ResultCode;
use Jmhc\Restful\ResultMsg;
use Jmhc\Restful\Traits\ResultThrow;
use Jmhc\Restful\Utils\Sdl;
use Jmhc\Restful\Utils\Token;

class CheckSdl
{
    use ResultThrow;

    public function handle(Request $request, Closure $next)
    {
        $token = Token::get();
        // token和用户id存在
        if (! empty($token) && ! empty($request->userInfo->id)) {
            if (! Sdl::getInstance()->verify($request->userInfo->id, $token)) {
                static::error(ResultMsg::SDL, ResultCode::SDL);
            }
        }

        return $next($request);
    }
}
