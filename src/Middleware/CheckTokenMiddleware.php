<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Jmhc\Restful\Contracts\ConstAttributeInterface;
use Jmhc\Restful\Contracts\ResultCodeInterface;
use Jmhc\Restful\Contracts\ResultMsgInterface;
use Jmhc\Restful\Contracts\UserModelInterface;
use Jmhc\Restful\Exceptions\ResultException;
use Jmhc\Restful\Traits\ResultThrowTrait;
use Jmhc\Restful\Utils\Token;
use Jmhc\Support\Utils\Collection;
use Jmhc\Support\Utils\Helper;

/**
 * 检测令牌中间件
 * @package Jmhc\Restful\Middleware
 */
class CheckTokenMiddleware
{
    use ResultThrowTrait;

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
                $this->error(
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
     * @throws BindingResolutionException
     */
    protected function check(Request $request)
    {
        // token
        $token = Token::get();

        // 判断token是否存在
        if (empty($token)) {
            $this->error(ResultMsgInterface::TOKEN_NO_EXISTS, ResultCodeInterface::TOKEN_NO_EXISTS);
        }

        // 解析token
        $parse = Token::parse($token);

        // 验证token
        $verify = Token::verify($parse);
        if ($verify !== true) {
            [$code, $msg] = $verify;
            $this->error($msg, $code);
        }

        // 解析[加密字符, 加密时间]
        [$id, $time] = $parse;

        // 判断token是否有效
        $info = $this->getUserInfo($id);
        if (empty($info)) {
            $this->error(ResultMsgInterface::TOKEN_INVALID, ResultCodeInterface::TOKEN_INVALID);
        } elseif ($info->is_freeze == ConstAttributeInterface::YES) {
            // 是否冻结
            $this->error(ResultMsgInterface::PROHIBIT_LOGIN, ResultCodeInterface::PROHIBIT_LOGIN);
        }

        // 判断是否刷新token
        $noticeTime = config('jmhc-api.token.notice_refresh_time', 0);
        if ((time() - $time) >= $noticeTime) {
            // 设置刷新的token
            $request->refreshToken = Token::create($id);
        }

        return $info;
    }

    /**
     * 获取用户信息
     * @param int $id
     * @return mixed
     */
    protected function getUserInfo(int $id)
    {
        return app()->get(UserModelInterface::class)->getInfoById($id);
    }
}
