<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Contracts\Container\BindingResolutionException;
use Jmhc\Restful\ResultCode;
use Jmhc\Restful\ResultMsg;
use Jmhc\Restful\Utils\Cipher\Token as TokenCipher;

/**
 * 令牌加密
 * @package Jmhc\Restful\Utils
 */
class Token
{
    /**
     * 获取token
     * @param string $name
     * @return string
     */
    public static function get(string $name = 'token')
    {
        $token = request()->bearerToken();
        if (empty($token)) {
            $token = request()->header(ucwords($name, '-'), '');
        }
        if (empty($token)) {
            $token = request()->input($name, '');
        }

        return $token;
    }

    /**
     * 创建token
     * @param int $id
     * @return string
     * @throws BindingResolutionException
     */
    public static function create(int $id)
    {
        $id .= ':' . time();
        return TokenCipher::getInstance()
            ->encrypt($id);
    }

    /**
     * 解析token
     * [加密数据, 加密时间]
     * @param string $token
     * @return array
     * @throws BindingResolutionException
     */
    public static function parse(string $token)
    {
        $str = TokenCipher::getInstance()
            ->decrypt($token);
        return explode(':', $str);
    }

    /**
     * 验证token
     * @param array $parse
     * @return array|bool
     */
    public static function verify(array $parse)
    {
        // 验证格式
        if (count($parse) != 2) {
            return [ResultCode::TOKEN_INVALID, ResultMsg::TOKEN_INVALID];
        }

        // 验证token是否有效
        $refreshTime = config('jmhc-api.token.allow_refresh_time', 0);
        if (($parse[1] + $refreshTime) < time()) {
            return [ResultCode::TOKEN_EXPIRE, ResultMsg::TOKEN_EXPIRE];
        }

        return true;
    }
}
