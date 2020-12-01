<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Http\Request;

/**
 * 请求参数
 * @package Jmhc\Restful\Utils
 */
class RequestParams
{
    public static function run(Request $request)
    {
        // 是否直接存在json格式的params参数
        $jsonParams = json_decode($request->input('params', ''), true);

        // 请求参数
        $params = $jsonParams ?? $request->all();

        // 请求解密
        if ($request->exists('params') && ! $jsonParams) {
            $params = Cipher::request($request->input('params', ''));
        }

        return $params;
    }
}