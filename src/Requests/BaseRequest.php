<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Jmhc\Restful\Utils\RequestParams;

/**
 * 基础表单请求
 * @package Jmhc\Restful\Requests
 */
class BaseRequest extends FormRequest
{
    /**
     * 验证数据
     * @return mixed|string
     */
    public function validationData()
    {
        return RequestParams::run($this);
    }
}