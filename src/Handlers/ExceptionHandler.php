<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

namespace Jmhc\Restful\Handlers;

use ErrorException;
use Exception;
use Illuminate\Contracts\Cache\Lock as LockContract;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Jmhc\Restful\Exceptions\ResultException;
use Jmhc\Restful\ResultCode;
use Jmhc\Restful\ResultMsg;
use Jmhc\Restful\Utils\Cipher;
use Jmhc\Restful\Utils\LogHelper;
use LogicException;
use ReflectionException;
use RuntimeException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * 异常处理
 * @package Jmhc\Restful\Handlers
 */
class ExceptionHandler extends Handler
{
    protected $code = ResultCode::ERROR;
    protected $msg = ResultMsg::ERROR;
    protected $data;

    protected $httpCode = ResultCode::HTTP_ERROR_CODE;

    public function report(Exception $e)
    {}

    public function render($request, Exception $e)
    {
        // 设置响应数据
        $this->response($e);

        // 响应数据
        $response = [
            'code' => $this->code,
            'msg' => $this->msg,
            'data' => $this->data,
        ];
        // 响应处理
        $response = $this->responseHandler($response);

        // 响应header
        $headers = [];

        // 判断刷新的token是否存在
        if(! empty($request->refreshToken)) {
            // 刷新token
            $this->refreshToken($request, $request->refreshToken, $headers);
            // 单设备登录操作
            $this->sdlHandler($request, $request->refreshToken);
        }

        // 解除请求锁定
        $this->unRequestLocke($request);

        return response()->json($response, $this->httpCode, $headers, JSON_UNESCAPED_UNICODE);
    }

    protected function response(Exception $e)
    {
        if ($e instanceof ResultException) {
            // 返回异常
            $this->code = $e->getCode();
            $this->msg = $e->getMessage();
            $this->data = $e->getData();
            $this->httpCode = $e->getHttpCode();
        } elseif ($e instanceof MaintenanceModeException) {
            // 系统维护中
            $this->code = ResultCode::MAINTENANCE;
            $this->msg = $e->getMessage() ?? ResultMsg::MAINTENANCE;
        } elseif ($e instanceof HttpException) {
            // 请求异常
            $this->code = ResultCode::ERROR;
            $this->msg = ResultMsg::INVALID_REQUEST;
        } elseif ($e instanceof QueryException) {
            // 数据库异常
            $this->code = ResultCode::SYS_EXCEPTION;
            $this->msg = ResultMsg::SYS_EXCEPTION;
            LogHelper::throwableSave(
                config('jmhc-api.db_exception_file_name', 'handle_db.exception'),
                $e
            );
        } elseif ($e instanceof ReflectionException || $e instanceof LogicException || $e instanceof RuntimeException || $e instanceof BindingResolutionException) {
            // 反射、逻辑、运行、绑定解析异常
            $this->code = ResultCode::SYS_EXCEPTION;
            $this->msg = ResultMsg::SYS_EXCEPTION;
            LogHelper::throwableSave(
                config('jmhc-api.exception_file_name', 'handle.exception'),
                $e
            );
        } elseif ($e instanceof FatalThrowableError || $e instanceof ErrorException) {
            // 发生错误
            $this->code = ResultCode::SYS_ERROR;
            $this->msg = ResultMsg::SYS_ERROR;
            LogHelper::throwableSave(
                config('jmhc-api.error_file_name', 'handle.error'),
                $e
            );
        }
    }

    /**
     * 响应处理
     * @param array $response
     * @return array|string
     */
    protected function responseHandler(array $response)
    {
        try {
            $response = Cipher::response($response);
        } catch (Throwable $e) {}

        return $response;
    }

    /**
     * 刷新token
     * @param $request
     * @param string $token
     * @param array $headers
     */
    protected function refreshToken($request, string $token, array &$headers)
    {
        $headers['Refresh-Token'] = $token;
    }

    /**
     * 单设备登录操作
     * @param $request
     * @param string $token
     */
    protected function sdlHandler($request, string $token)
    {}

    /**
     * 解除请求锁定
     * @param $request
     */
    protected function unRequestLocke($request)
    {
        if ($this->code != ResultCode::REQUEST_LOCKED &&
            $request->requestLock instanceof LockContract) {
            $request->requestLock->forceRelease();
        }
    }
}
