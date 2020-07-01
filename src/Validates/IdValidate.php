<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Validates;

use Illuminate\Support\Facades\Validator;

class IdValidate extends BaseValidate
{
    public function rules(): array
    {
        return [
            'id' => 'bail|required|integer|gt:0',
        ];
    }

    /**
     * 检测包含0的id
     * @param array $data
     * @return array
     */
    public function checkZero(array $data)
    {
        $rules['id'] = 'bail|required|integer';
        return Validator::make($data, $rules, $this->messages(), $this->attributes())->validate();
    }
}