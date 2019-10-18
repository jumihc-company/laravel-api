<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Utils\Cipher;

class Runtime extends Base
{
    /**
     * 加密字符串
     * @param string $str
     * @return string
     */
    public function encrypt(string $str)
    {
        return rawurlencode(openssl_encrypt(
            $str , $this->method, $this->key, 0, $this->iv
        ));
    }

    /**
     * 解密字符串
     * @param string $str
     * @return string
     */
    public function decrypt(string $str)
    {
        return openssl_decrypt(
            rawurldecode($str), $this->method, $this->key, 0, $this->iv
        );
    }
}
