<?php
/**
 * User: YL
 * Date: 2019/10/16
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
     * 规则
     * @var array
     */
    protected $rules = [];

    /**
     * 消息
     * @var array
     */
    protected $messages = [];

    /**
     * 属性
     * @var array
     */
    protected $attributes = [];

    /**
     * 验证
     * @param array $data
     * @return array
     */
    public function check(array $data)
    {
        return Validator::make($data, $this->rules, $this->messages, $this->attributes)->validate();
    }

    /**
     * 选取需要的规则
     * @param array $fields
     * @return array
     */
    protected function only(array $fields)
    {
        return array_filter($this->rules, function ($key) use ($fields) {
            return $this->inArray($key, $fields);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * 移除需要的规则
     * @param array $fields
     * @return array
     */
    protected function remove(array $fields)
    {
        return array_filter($this->rules, function ($key) use ($fields) {
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
