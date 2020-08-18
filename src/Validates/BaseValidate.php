<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Validates;

use Illuminate\Support\Facades\Validator;
use Jmhc\Support\Traits\InstanceTrait;

/**
 * 基础验证器
 * @package Jmhc\Restful\Validates
 */
class BaseValidate
{
    use InstanceTrait;

    /**
     * 验证
     * @param array $data
     * @return array
     */
    public function check(array $data)
    {
        return $this->validate($data);
    }

    /**
     * 规则
     * @return array
     */
    public function rules() : array
    {
        return [];
    }

    /**
     * 消息
     * @return array
     */
    public function messages() : array
    {
        return [];
    }

    /**
     * 属性
     * @return array
     */
    public function attributes() : array
    {
        return [];
    }

    /**
     * 验证
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return mixed
     */
    protected function validate(array $data, array $rules = [], array $messages = [], array $customAttributes = [])
    {
        // 参数默认值
        empty($rules) && $rules = $this->rules();
        empty($messages) && $messages = $this->messages();
        empty($customAttributes) && $customAttributes = $this->attributes();

        // 验证器
        $validate = Validator::make($data, $rules, $messages, $customAttributes);

        // 验证
        $validate->validate();

        // 返回验证数据
        return $validate->validated();
    }

    /**
     * 选取需要的规则
     * @param array $fields
     * @param array $data
     * @return array
     */
    protected function only(array $fields, array $data = [])
    {
        if (empty($data)) {
            $data = $this->rules();
        }

        return array_filter($data, function ($key) use ($fields) {
            return $this->inArray($key, $fields);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * 移除需要的规则
     * @param array $fields
     * @param array $data
     * @return array
     */
    protected function remove(array $fields, array $data = [])
    {
        if (empty($data)) {
            $data = $this->rules();
        }

        return array_filter($data, function ($key) use ($fields) {
            return ! $this->inArray($key, $fields);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * 判断是否在字符串里
     * @param string $key
     * @param array $fields
     * @return bool
     */
    private function inArray(string $key, array $fields)
    {
        $res = in_array($key, $fields);
        if ($res) {
            return $res;
        }

        foreach ($fields as $field) {
            if (stripos($key, $field . '.') !== false) {
                $res = true;
                break;
            }
        }

        return $res;
    }
}
