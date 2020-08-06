<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Utils;

use Jmhc\Restful\Contracts\ResultCodeInterface;
use Jmhc\Restful\Contracts\ResultMsgInterface;
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
     * @param string $scene
     * @return string
     */
    public static function create(int $id, string $scene = 'api')
    {
        $id .= ':' . time() . ':' . $scene;
        return TokenCipher::getInstance()
            ->encrypt($id);
    }

    /**
     * 解析token
     * [加密数据, 加密时间]
     * @param string $token
     * @return array
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
     * @param string $scene
     * @return array|bool
     */
    public static function verify(array $parse, string $scene = 'api')
    {
        // 验证格式
        if (count($parse) != 3) {
            return [ResultCodeInterface::TOKEN_INVALID, ResultMsgInterface::TOKEN_INVALID];
        }

        // [加密字符, 加密时间, 加密场景]
        [$id, $time, $parseScene] = $parse;

        // 场景不同
        if ($parseScene != $scene) {
            return [ResultCodeInterface::TOKEN_INVALID, ResultMsgInterface::TOKEN_INVALID];
        }

        // 验证token是否有效
        $refreshTime = config('jmhc-api.token.allow_refresh_time', 0);
        if (((int) $time + $refreshTime) < time()) {
            return [ResultCodeInterface::TOKEN_EXPIRE, ResultMsgInterface::TOKEN_EXPIRE];
        }

        return true;
    }
}
