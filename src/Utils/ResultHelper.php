<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Utils;

use Jmhc\Restful\Contracts\ResultCodeInterface;
use Jmhc\Restful\Contracts\ResultMsgInterface;

/**
 * 返回结果辅助
 * @package Jmhc\Restful\Utils
 */
class ResultHelper
{
    /**
     * 抛出成功异常
     * @param $data
     * @param string $msg
     * @param int $code
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public static function success($data = null, string $msg = ResultMsgInterface::SUCCESS, int $code = ResultCodeInterface::SUCCESS)
    {
        return static::abort($code, $msg, $data);
    }

    /**
     * 抛出失败异常
     * @param string $msg
     * @param int $code
     * @param array $data
     * @param int $httpCode
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public static function error(string $msg, int $code = ResultCodeInterface::ERROR, $data = null, int $httpCode = ResultCodeInterface::HTTP_ERROR_CODE)
    {
        return static::abort($code, $msg, $data, $httpCode);
    }

    /**
     * 抛出无数据异常
     * @param string $msg
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public static function noData(string $msg = ResultMsgInterface::NO_DATA)
    {
        return static::abort(ResultCodeInterface::NO_DATA, $msg, null);
    }

    /**
     * 抛出异常
     * @param int $code
     * @param string $msg
     * @param $data
     * @param int $httpCode
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    private static function abort(int $code, string $msg, $data, int $httpCode = ResultCodeInterface::HTTP_SUCCESS_CODE)
    {
        $response = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];

        return response()->json($response, $httpCode, [], JSON_UNESCAPED_UNICODE);
    }
}