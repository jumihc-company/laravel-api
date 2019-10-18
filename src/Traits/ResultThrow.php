<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Traits;

use Jmhc\Restful\ResultCode;
use Jmhc\Restful\ResultException;
use Jmhc\Restful\ResultMsg;

trait ResultThrow
{
    /**
     * 抛出成功异常
     * @param $data
     * @param string $msg
     * @param int $code
     * @throws ResultException
     */
    protected static function success($data = null, string $msg = ResultMsg::SUCCESS, int $code = ResultCode::SUCCESS)
    {
        static::abort($code, $msg, $data);
    }

    /**
     * 抛出失败异常
     * @param string $msg
     * @param int $code
     * @param array $data
     * @param int $httpCode
     * @throws ResultException
     */
    protected static function error(string $msg, int $code = ResultCode::ERROR, $data = null, int $httpCode = ResultCode::HTTP_ERROR_CODE)
    {
        static::abort($code, $msg, $data, $httpCode);
    }

    /**
     * 抛出无数据异常
     * @param string $msg
     * @throws ResultException
     */
    protected static function noData(string $msg = ResultMsg::NO_DATA)
    {
        static::abort(ResultCode::NO_DATA, $msg, null);
    }

    /**
     * 抛出异常
     * @param int $code
     * @param string $msg
     * @param $data
     * @param int $httpCode
     * @throws ResultException
     */
    private static function abort(int $code, string $msg, $data, int $httpCode = ResultCode::HTTP_SUCCESS_CODE)
    {
        throw new ResultException($code, $msg, $data, $httpCode);
    }
}
