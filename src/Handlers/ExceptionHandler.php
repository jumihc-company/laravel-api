<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Handlers;

use Error;
use ErrorException;
use Illuminate\Contracts\Cache\Lock as LockContract;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Jmhc\Log\Log;
use Jmhc\Restful\Contracts\ResultCodeInterface;
use Jmhc\Restful\Exceptions\ResultException;
use Jmhc\Restful\Utils\Cipher;
use LogicException;
use ReflectionException;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * 异常处理
 * @package Jmhc\Restful\Handlers
 */
class ExceptionHandler extends Handler
{
    protected $code;
    protected $msg;
    protected $data;

    protected $httpCode;

    private $key;

    public function report(Throwable $e)
    {}

    public function render($request, Throwable $e)
    {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        // 重置属性
        $this->resetProperty();

        // 设置响应数据
        $this->response($e);

        // 响应数据
        $response = [
            'code' => $this->code,
            'msg' => $this->msg,
            'data' => $this->data,
        ];
        // 如果是调试模式
        if (config('jmhc-api.exception_debug', true)) {
            $response['debug'] = $this->responseDebug($e);
        }
        // 响应处理
        $response = $this->responseHandler($response);

        // 响应头
        $headers = [];

        // 判断刷新的令牌是否存在
        if (! empty($request->refreshToken)) {
            // 刷新令牌
            $this->refreshToken($request, $request->refreshToken, $headers);
            // 单设备登录处理
            $this->sdlHandler($request, $request->refreshToken);
        }

        // 解除请求锁定
        $this->unRequestLock($request);

        // 响应前处理
        $this->responseBeforeHandler($request);

        return response()->json($response, $this->httpCode, $headers, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 自定义响应
     * @param Throwable $e
     */
    protected function customResponse(Throwable $e)
    {}

    /**
     * 返回调试数据
     * @param Throwable $e
     * @return array
     */
    protected function responseDebug(Throwable $e)
    {
        return [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'message' => $e->getMessage(),
            'trace' => $e->getTrace(),
        ];
    }

    /**
     * 刷新令牌
     * @param $request
     * @param string $token
     * @param array $headers
     */
    protected function refreshToken($request, string $token, array &$headers)
    {
        $headers['Refresh-Token'] = $token;
    }

    /**
     * 单设备登录处理
     * @param $request
     * @param string $token
     */
    protected function sdlHandler($request, string $token)
    {}

    /**
     * 解除请求锁定
     * @param $request
     */
    protected function unRequestLock($request)
    {
        if ($this->code != ResultCodeInterface::REQUEST_LOCKED &&
            $request->requestLock instanceof LockContract) {
            $request->requestLock->forceRelease();
        }
    }

    /**
     * 响应前处理
     * @param $request
     */
    protected function responseBeforeHandler($request)
    {}

    /**
     * 生成秘钥
     * @return string
     */
    private function buildKey()
    {
        return md5(sprintf(
            '%s-%s-%s-%s',
            $this->code,
            $this->msg,
            json_encode($this->data),
            $this->httpCode
        ));
    }

    /**
     * 验证秘钥
     * @return bool
     */
    private function validateKey()
    {
        return $this->key === $this->buildKey();
    }

    /**
     * 重置属性
     */
    private function resetProperty()
    {
        $this->code = ResultCodeInterface::ERROR;
        $this->msg = jmhc_api_lang_messages_trans('error');
        $this->data = null;
        $this->httpCode = ResultCodeInterface::HTTP_ERROR_CODE;
        $this->key = $this->buildKey();
    }

    /**
     * 设置响应数据
     * @param Throwable $e
     */
    private function response(Throwable $e)
    {
        // 自定义响应
        $this->customResponse($e);

        // 秘钥不同,阻止执行
        if (! $this->validateKey()) {
            return;
        }

        if ($e instanceof ResultException) {
            // 返回异常
            $this->code = $e->getCode();
            $this->msg = $e->getMessage();
            $this->data = $e->getData();
            $this->httpCode = $e->getHttpCode();
        } elseif ($e instanceof MaintenanceModeException) {
            // 系统维护中
            $this->code = ResultCodeInterface::MAINTENANCE;
            $this->msg = $e->getMessage() ?: jmhc_api_lang_messages_trans('maintenance');
        } elseif ($e instanceof HttpException) {
            // 请求异常
            $this->code = ResultCodeInterface::ERROR;
            $this->msg = jmhc_api_lang_messages_trans('invalid_request');
        } elseif ($e instanceof QueryException) {
            // 数据库异常
            $this->code = ResultCodeInterface::SYS_EXCEPTION;
            $this->msg = jmhc_api_lang_messages_trans('sys_exception');
            Log::name(
                config('jmhc-api.db_exception_file_name', 'handle_db.exception')
            )
                ->withDateToName()
                ->withRequestInfo(
                    config('jmhc-api.db_exception_request_message', false)
                )
                ->throwable($e);
        } elseif ($e instanceof ValidationException) {
            // 验证器异常
            $this->msg = $e->validator->errors()->first();
        } elseif ($e instanceof ReflectionException || $e instanceof LogicException || $e instanceof RuntimeException || $e instanceof BindingResolutionException) {
            // 反射、逻辑、运行、绑定解析异常
            $this->code = ResultCodeInterface::SYS_EXCEPTION;
            $this->msg = jmhc_api_lang_messages_trans('sys_exception');
            Log::name(
                config('jmhc-api.exception_file_name', 'handle.exception')
            )
                ->withDateToName()
                ->withRequestInfo(
                    config('jmhc-api.exception_request_message', false)
                )
                ->throwable($e);
        } elseif ($e instanceof Error || $e instanceof ErrorException) {
            // 发生错误
            $this->code = ResultCodeInterface::SYS_ERROR;
            $this->msg = jmhc_api_lang_messages_trans('sys_error');
            Log::name(
                config('jmhc-api.error_file_name', 'handle.error')
            )
                ->withDateToName()
                ->withRequestInfo(
                    config('jmhc-api.error_request_message', false)
                )
                ->throwable($e);
        }
    }

    /**
     * 响应处理
     * @param array $response
     * @return array|string
     */
    private function responseHandler(array $response)
    {
        try {
            $response = Cipher::response($response);
        } catch (Throwable $e) {}

        return $response;
    }
}
