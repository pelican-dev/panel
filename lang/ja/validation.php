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

    'accepted' => ':attribute を受け入れる必要があります。',
    'active_url' => ':attribute は有効な URL ではありません。',
    'after' => ':attribute は :date より後の日付でなければなりません。',
    'after_or_equal' => ':attribute は :date 以降の日付でなければなりません。',
    'alpha' => ':attribute は英字のみで構成されなければなりません。',
    'alpha_dash' => ':attribute は英字、数字、及びダッシュのみで構成されなければなりません。',
    'alpha_num' => ':attribute は英数字のみで構成されなければなりません。',
    'array' => ':attribute は配列でなければなりません。',
    'before' => ':attribute は :date より前の日付でなければなりません。',
    'before_or_equal' => ':attribute は :date 以前の日付でなければなりません。',
    'between' => [
        'numeric' => ':attribute は :min から :max の間でなければなりません。',
        'file' => ':attribute は :min から :max キロバイトの間でなければなりません。',
        'string' => ':attribute は :min 文字から :max 文字の間でなければなりません。',
        'array' => ':attribute の項目数は :min から :max の間でなければなりません。',
    ],

    'confirmed' => ':attribute の確認が一致しません。',
    'date' => ':attribute は有効な日付ではありません。',
    'date_format' => ':attribute は :format の形式と一致しません。',
    'different' => ':attribute と :other は異なっている必要があります。',
    'digits' => ':attribute は :digits 桁でなければなりません。',
    'digits_between' => ':attribute は :min 桁から :max 桁の間でなければなりません。',
    'dimensions' => ':attribute の画像サイズが無効です。',

    'email' => ':attribute には有効なメールアドレスを指定してください。',

    'file' => ':attribute はファイルでなければなりません。',
    'filled' => ':attribute には値を入力する必要があります。',
    'image' => ':attribute は画像ファイルでなければなりません。',

    'in_array' => ':attribute は :other に存在しません。',
    'integer' => ':attribute は整数でなければなりません。',
    'ip' => ':attribute には有効な IP アドレスを指定してください。',
    'json' => ':attribute は有効な JSON 文字列でなければなりません。',
    'max' => [
        'numeric' => ':attribute は :max 以下でなければなりません。',
        'file' => ':attribute は :max キロバイト以下でなければなりません。',
        'string' => ':attribute は :max 文字以下でなければなりません。',
        'array' => ':attribute の項目数は :max 個以下でなければなりません。',
    ],
    'mimes' => ':attribute は :values の形式のファイルでなければなりません。',
    'mimetypes' => ':attribute は :values の形式のファイルでなければなりません。',
    'min' => [
        'numeric' => ':attribute は少なくとも :min でなければなりません。',
        'file' => ':attribute は少なくとも :min キロバイトでなければなりません。',
        'string' => ':attribute は少なくとも :min 文字でなければなりません。',
        'array' => ':attribute には少なくとも :min 個の項目が必要です。',
    ],

    'numeric' => ':attribute は数値でなければなりません。',

    'regex' => ':attribute の形式が正しくありません。',

    'required_with_all' => ':values が存在する場合、:attribute は必須です。',

    'same' => ':attribute と :other は一致している必要があります。',
    'size' => [
        'numeric' => ':attribute は :size でなければなりません。',
        'file' => ':attribute は :size キロバイトでなければなりません。',
        'string' => ':attribute は :size 文字でなければなりません。',
        'array' => ':attribute は :size 個の項目を含む必要があります。',
    ],
    'string' => ':attribute は文字列でなければなりません。',
    'timezone' => ':attribute には有効なタイムゾーンを指定してください。',

    'url' => ':attribute の形式が正しくありません。',

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
        'variable_value' => ':env 変数',
        'invalid_password' => 'このアカウントのパスワードが無効です。',
    ],
];
