<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Jmhc\Restful\Models\UserModel;
use Jmhc\Restful\ResultCode;
use Jmhc\Restful\ResultException;
use Jmhc\Restful\ResultMsg;
use Jmhc\Restful\Traits\ResultThrow;
use Jmhc\Restful\Utils\Collection;
use Jmhc\Restful\Utils\Env;
use Jmhc\Restful\Utils\Helper;
use Jmhc\Restful\Utils\Token;

class CheckToken
{
    use ResultThrow;

    public function handle(Request $request, Closure $next, $force = true)
    {
        try {
            // 用户信息
            $request->userInfo = $this->check($request);
        } catch (ResultException $e) {
            if (! Helper::boolean($force)) {
                // 用户信息
                $request->userInfo = new Collection();
            } else {
                static::error(
                    $e->getMessage(),
                    $e->getCode(),
                    $e->getData(),
                    $e->getHttpCode()
                );
            }
        }

        return $next($request);
    }

    /**
     * 验证
     * @param Request $request
     * @return Builder|Model|object|null
     * @throws ResultException
     */
    protected function check(Request $request)
    {
        // token
        $token = Token::get(
            Env::get('jmhc.request.token_name', 'token')
        );

        // 判断token是否存在
        if (empty($token)) {
            static::error(ResultMsg::TOKEN_NO_EXISTS, ResultCode::TOKEN_NO_EXISTS);
        }

        // 解析token
        $parse = Token::parse($token);

        // 验证token
        $verify = Token::verify($parse);
        if ($verify !== true) {
            [$code, $msg] = $verify;
            static::error($msg, $code);
        }

        // 解析[加密字符, 加密时间]
        [$id, $time] = $parse;

        // 判断token是否有效
        $info = UserModel::getInfoById($id);
        if (empty($info)) {
            static::error(ResultMsg::TOKEN_INVALID, ResultCode::TOKEN_INVALID);
        } elseif ($info->status != UserModel::YES) {
            static::error(ResultMsg::PROHIBIT_LOGIN, ResultCode::PROHIBIT_LOGIN);
        }

        // 判断是否刷新token
        $noticeTime = Env::get('jmhc.token.notice_refresh_time', 0);
        if ((time() - $time) >= $noticeTime) {
            // 设置刷新的token
            $request->refreshToken = Token::create($id);
        }

        return $info;
    }
}
