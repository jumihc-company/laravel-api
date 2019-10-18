<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => '必须接受 :attribute。',
    'active_url' => ':attribute 不是有效的URL。',
    'after' => ':attribute 必须是 :date 之后的日期。',
    'after_or_equal' => ':attribute 必须是 :date 之后的日期或等于 :date。',
    'alpha' => ':attribute 只能包含字母。',
    'alpha_dash' => ':attribute 只能包含字母、数字、破折号和下划线。',
    'alpha_num' => ':attribute 只能包含字母和数字。',
    'array' => ':attribute 必须是数组。',
    'before' => ':attribute 必须是 :date 之前的日期。',
    'before_or_equal' => ':attribute 必须是 :date 之前的日期或等于 :date。',
    'between' => [
        'numeric' => ':attribute 必须在 :min 和 :max 之间。',
        'file' => ':attribute 必须在 :min 和 :max 千字节之间。',
        'string' => ':attribute 必须在 :min 和 :max 字符之间。',
        'array' => ':attribute 必须在 :min 和 :max 项之间。',
    ],
    'boolean' => ':attribute 字段必须为 true 或 false。',
    'confirmed' => ':attribute 确认字段不匹配。',
    'date' => ':attribute 不是有效的日期。',
    'date_equals' => ':attribute 必须等于 :date。',
    'date_format' => ':attribute 与格式不匹配 :format。',
    'different' => ':attribute 和 :other 必须不同。',
    'digits' => ':attribute 值 :digits 必须是数字。',
    'digits_between' => ':attribute 长度必须在 :min 和 :max 之间。',
    'dimensions' => ':attribute 图像尺寸不符合。',
    'distinct' => ':attribute 字段具有重复值。',
    'email' => ':attribute 必须是一个有效的电子邮件地址。',
    'ends_with' => ':attribute 必须以下列值之一结束: :values',
    'exists' => '所选 :attribute 无效。',
    'file' => ':attribute 必须是文件。',
    'filled' => ':attribute 字段不能为空。',
    'gt' => [
        'numeric' => ':attribute 必须大于 :value。',
        'file' => ':attribute 必须大于 :value 千字节。',
        'string' => ':attribute 必须大于 :value 位字符串。',
        'array' => ':attribute 必须大于 :value 项。',
    ],
    'gte' => [
        'numeric' => ':attribute 必须大于或等于 :value。',
        'file' => ':attribute 必须大于或等于 :value 千字节。',
        'string' => ':attribute 必须大于或等于 :value 位字符串。',
        'array' => ':attribute 必须具有 :value 项或更多。',
    ],
    'image' => ':attribute 必须是图像。',
    'in' => ':attribute 字段必须在 :values 之间。',
    'in_array' => ':attribute 字段必须在 :other 之间。',
    'integer' => ':attribute 必须是整数。',
    'ip' => ':attribute 必须是一个有效的IP地址。',
    'ipv4' => ':attribute 必须是有效的IPv4地址。',
    'ipv6' => ':attribute 必须是有效的IPv6地址吗。',
    'json' => ':attribute 必须是一个有效的JSON字符串。',
    'lt' => [
        'numeric' => ':attribute 必须小于 :value。',
        'file' => ':attribute 必须小于 :value 千字节。',
        'string' => ':attribute 必须小于 :value 位字符串。',
        'array' => ':attribute 必须小于 :value 项。',
    ],
    'lte' => [
        'numeric' => ':attribute 必须小于或等于 :value。',
        'file' => ':attribute 必须小于或等于 :value 千字节。',
        'string' => ':attribute 必须小于或等于 :value 位字符串。',
        'array' => ':attribute 必须小于或等于 :value 项。',
    ],
    'max' => [
        'numeric' => ':attribute 不能大于 :max。',
        'file' => ':attribute 不能大于 :max 千字节。',
        'string' => ':attribute 不能大于 :max 位字符串。',
        'array' => ':attribute 不能超过 :max 项。',
    ],
    'mimes' => ':attribute 必须是以下类型的文件: :values。',
    'mimetypes' => ':attribute 必须是以下类型的文件: :values。',
    'min' => [
        'numeric' => ':attribute 不能小于 :min。',
        'file' => ':attribute 不能小于 :min 千字节。',
        'string' => ':attribute 不能小于 :min 位字符串。',
        'array' => ':attribute 不能小于 :min 项。',
    ],
    'not_in' => ':attribute 不允许在 :values 之间。',
    'not_regex' => ':attribute 格式无效。',
    'numeric' => ':attribute 必须是数字。',
    'present' => ':attribute 字段必须存在。',
    'regex' => ':attribute 格式无效。',
    'required' => ':attribute 字段必须。',
    'required_if' => ':attribute 字段在 :other 为 :value 时必须。',
    'required_unless' => ':attribute 字段是必须的，除非 :other 在 :values 之中。',
    'required_with' => '当 :values 出现时 :attribute 字段必须。',
    'required_with_all' => '当 :values 全部出现时 :attribute 字段必须。',
    'required_without' => '当 :values 不出现时 :attribute 字段必须。',
    'required_without_all' => '当 :values 全部不出现时 :attribute 字段必须。',
    'same' => ':attribute 和 :other 必须匹配。',
    'size' => [
        'numeric' => ':attribute 必须是 :size。',
        'file' => ':attribute 必须是 :size 千字节。',
        'string' => ':attribute 必须是 :size 位字符串。',
        'array' => ':attribute 必须包含 :size 项。',
    ],
    'starts_with' => ':attribute 必须以下列值之一开始: :values',
    'string' => ':attribute 必须是字符串。',
    'timezone' => ':attribute 必须是有效的时区。',
    'unique' => ':attribute 已经存在。',
    'uploaded' => ':attribute 上传失败。',
    'url' => ':attribute 格式无效。',
    'uuid' => ':attribute 必须是有效的 UUID。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'images' => ':attribute 格式不正确。',

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
