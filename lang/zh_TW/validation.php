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

    'accepted' => '必須同意 :attribute。',
    'active_url' => ':attribute 不是有效的網址。',
    'after' => ':attribute 必須是在 :date 後的日期。',
    'after_or_equal' => ':attribute 必須是一個在 :date 或之後的日期。',
    'alpha' => ':attribute 只能包含字母。',
    'alpha_dash' => ':attribute 只允許數字，字母，和下劃線。',
    'alpha_num' => ':attribute 只能包含數字和字母。',
    'array' => ':attribute 必須是陣列。',
    'before' => ':attribute 必須在 :date 之前',
    'before_or_equal' => ':attribute 必須是一個在 :date 或之前的日期。',
    'between' => [
        'numeric' => ':attribute 必須介於 :min 和 :max 之間。',
        'file' => ':attribute 必須介於 :min 至 :max KB 之間。',
        'string' => ':attribute 必須介於 :min 到 :max 個字元之間。',
        'array' => ':attribute 的數目必須在 :min 到 :max 之間。',
    ],

    'confirmed' => ':attribute 的確認欄位內容不符。',
    'date' => ':attribute 不是有效的日期。',
    'date_format' => ':attribute 不符合 :format 的格式',
    'different' => ':attribute 與 :other 必須不同。',
    'digits' => ':attribute 必須是 :digits 位數字。',
    'digits_between' => ':attribute 必須介於 :min 至 :max 位數字。',
    'dimensions' => ':attribute 的圖片尺寸無效。',

    'email' => ':attribute 必須是有效的電子郵件地址。',

    'file' => ':attribute 必須是一個檔案。',
    'filled' => ':attribute 欄位是必填的。',
    'image' => ':attribute 必須是圖片。',

    'in_array' => ':attribute 沒有在 :other 中。',
    'integer' => ':attribute 必須是整數。',
    'ip' => ':attribute 必須是一個有效的 IP 地址。',
    'json' => ':attribute 必須是有效的 JSON 字串。',
    'max' => [
        'numeric' => ':attribute 不能大於 :max。',
        'file' => ':attribute 不能超過 :max KB。',
        'string' => ':attribute 不能大於 :max 字元。',
        'array' => ':attribute 不能有超過 :max 個的項目。',
    ],
    'mimes' => ':attribute 檔案類型必須是 :values',
    'mimetypes' => ':attribute 檔案類型必須是 :values',
    'min' => [
        'numeric' => ':attribute 必須至少是 :min。',
        'file' => ':attribute 必須至少為 :min KB。',
        'string' => ':attribute 最少需要有 :min 個字元。',
        'array' => ':attribute 至少需要有 :min 個項目。',
    ],

    'numeric' => ':attribute 必須是數字。',

    'regex' => ':attribute 的格式錯誤。',

    'required_with_all' => '當 :values 存在時，:attribute 欄位是必填的。',

    'same' => ':attribute 與 :other 必須匹配。',
    'size' => [
        'numeric' => ':attribute 必須為 :size。',
        'file' => ':attribute 的大小必須是 :size KB。',
        'string' => ':attribute 必須是 :size 個字元。',
        'array' => ':attribute 必須包含 :size 個項目。',
    ],
    'string' => ':attribute 必須是字串。',
    'timezone' => ':attribute 必須是有效的區域。',

    'url' => ':attribute 的格式錯誤。',

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
        'variable_value' => ':env 環境變數',
        'invalid_password' => '提供的密碼對此帳號無效。',
    ],
];
