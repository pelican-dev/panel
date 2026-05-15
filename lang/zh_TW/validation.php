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

    'accepted' => ':attribute 必須被接受。',
    'active_url' => ':attribute 不是一個有效的 URL。',
    'after' => ':attribute 必須是 :date 之後的日期。',
    'after_or_equal' => ':attribute 必須是 :date 之後或相同的日期。',
    'alpha' => ':attribute 只能包含字母。',
    'alpha_dash' => ':attribute 只能包含字母、數字和破折號。',
    'alpha_num' => ':attribute 只能包含字母和數字。',
    'array' => ':attribute 必須是一個陣列。',
    'before' => ':attribute 必須是 :date 之前的日期。',
    'before_or_equal' => ':attribute 必須是 :date 之前或相同的日期。',
    'between' => [
        'numeric' => ':attribute 必須在 :min 和 :max 之間。',
        'file' => ':attribute 必須在 :min 和 :max KB 之間。',
        'string' => ':attribute 必須在 :min 和 :max 個字元之間。',
        'array' => ':attribute 必須有 :min 到 :max 個項目。',
    ],

    'confirmed' => ':attribute 確認不相符。',
    'date' => ':attribute 不是一個有效的日期。',
    'date_format' => ':attribute 不符合格式 :format。',
    'different' => ':attribute 和 :other 必須不同。',
    'digits' => ':attribute 必須是 :digits 位數字。',
    'digits_between' => ':attribute 必須在 :min 和 :max 位數字之間。',
    'dimensions' => ':attribute 的圖片尺寸無效。',

    'email' => ':attribute 必須是一個有效的電子郵件地址。',

    'file' => ':attribute 必須是一個檔案。',
    'filled' => ':attribute 欄位是必填的。',
    'image' => ':attribute 必須是一張圖片。',

    'in_array' => ':attribute 欄位在 :other 中不存在。',
    'integer' => ':attribute 必須是一個整數。',
    'ip' => ':attribute 必須是一個有效的 IP 地址。',
    'json' => ':attribute 必須是一個有效的 JSON 字串。',
    'max' => [
        'numeric' => ':attribute 不能大於 :max。',
        'file' => ':attribute 不能大於 :max KB。',
        'string' => ':attribute 不能大於 :max 個字元。',
        'array' => ':attribute 不能有超過 :max 個項目。',
    ],
    'mimes' => ':attribute 必須是一個類型為: :values 的檔案。',
    'mimetypes' => ':attribute 必須是一個類型為: :values 的檔案。',
    'min' => [
        'numeric' => ':attribute 必須至少為 :min。',
        'file' => ':attribute 必須至少為 :min KB。',
        'string' => ':attribute 必須至少為 :min 個字元。',
        'array' => ':attribute 必須至少有 :min 個項目。',
    ],

    'numeric' => ':attribute 必須是一個數字。',

    'regex' => ':attribute 格式無效。',

    'required_with_all' => '當存在 :values 時，:attribute 欄位是必填的。',

    'same' => ':attribute 和 :other 必須相符。',
    'size' => [
        'numeric' => ':attribute 必須是 :size。',
        'file' => ':attribute 必須是 :size KB。',
        'string' => ':attribute 必須是 :size 個字元。',
        'array' => ':attribute 必須包含 :size 個項目。',
    ],
    'string' => ':attribute 必須是一個字串。',
    'timezone' => ':attribute 必須是一個有效的時區。',

    'url' => ':attribute 格式無效。',

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
        'variable_value' => ':env 變數',
        'invalid_password' => '提供的密碼對此帳戶無效。',
    ],
];
