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

    'accepted' => ':attribute 必须被接受。',
    'active_url' => ':attribute 不是一个有效的 URL。',
    'after' => ':attribute 必须是 :date 之后的日期。',
    'after_or_equal' => ':attribute 必须是 :date 之后或相同的日期。',
    'alpha' => ':attribute 只能包含字母。',
    'alpha_dash' => ':attribute 只能包含字母、数字和破折号。',
    'alpha_num' => ':attribute 只能包含字母和数字。',
    'array' => ':attribute 必须是一个数组。',
    'before' => ':attribute 必须是 :date 之前的日期。',
    'before_or_equal' => ':attribute 必须是 :date 之前或相同的日期。',
    'between' => [
        'numeric' => ':attribute 必须在 :min 和 :max 之间。',
        'file' => ':attribute 必须在 :min 和 :max KB 之间。',
        'string' => ':attribute 必须在 :min 和 :max 个字符之间。',
        'array' => ':attribute 必须有 :min 到 :max 个项。',
    ],

    'confirmed' => ':attribute 确认不匹配。',
    'date' => ':attribute 不是一个有效的日期。',
    'date_format' => ':attribute 不匹配格式 :format。',
    'different' => ':attribute 和 :other 必须不同。',
    'digits' => ':attribute 必须是 :digits 位数字。',
    'digits_between' => ':attribute 必须在 :min 和 :max 位数字之间。',
    'dimensions' => ':attribute 的图像尺寸无效。',

    'email' => ':attribute 必须是一个有效的电子邮件地址。',

    'file' => ':attribute 必须是一个文件。',
    'filled' => ':attribute 字段是必填的。',
    'image' => ':attribute 必须是一张图片。',

    'in_array' => ':attribute 字段在 :other 中不存在。',
    'integer' => ':attribute 必须是一个整数。',
    'ip' => ':attribute 必须是一个有效的 IP 地址。',
    'json' => ':attribute 必须是一个有效的 JSON 字符串。',
    'max' => [
        'numeric' => ':attribute 不能大于 :max。',
        'file' => ':attribute 不能大于 :max KB。',
        'string' => ':attribute 不能大于 :max 个字符。',
        'array' => ':attribute 不能有超过 :max 个项。',
    ],
    'mimes' => ':attribute 必须是一个类型为: :values 的文件。',
    'mimetypes' => ':attribute 必须是一个类型为: :values 的文件。',
    'min' => [
        'numeric' => ':attribute 必须至少为 :min。',
        'file' => ':attribute 必须至少为 :min KB。',
        'string' => ':attribute 必须至少为 :min 个字符。',
        'array' => ':attribute 必须至少有 :min 个项。',
    ],

    'numeric' => ':attribute 必须是一个数字。',

    'regex' => ':attribute 格式无效。',

    'required_with_all' => '当存在 :values 时，:attribute 字段是必填的。',

    'same' => ':attribute 和 :other 必须匹配。',
    'size' => [
        'numeric' => ':attribute 必须是 :size。',
        'file' => ':attribute 必须是 :size KB。',
        'string' => ':attribute 必须是 :size 个字符。',
        'array' => ':attribute 必须包含 :size 个项。',
    ],
    'string' => ':attribute 必须是一个字符串。',
    'timezone' => ':attribute 必须是一个有效的时区。',

    'url' => ':attribute 格式无效。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

    // Internal validation logic for Panel
    'internal' => [
        'variable_value' => ':env 变量',
        'invalid_password' => '提供的密码对此帐户无效。',
    ],
];
