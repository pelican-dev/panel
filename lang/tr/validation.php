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

    'accepted' => ':attribute kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL olmalıdır.',
    'after' => ':attribute, :date tarihinden sonra olmalıdır.',
    'after_or_equal' => ':attribute, :date tarihi ile aynı veya sonrası olmalıdır.',
    'alpha' => ':attribute yalnızca harfler içerebilir.',
    'alpha_dash' => ':attribute yalnızca harfler, sayılar ve tireler içerebilir.',
    'alpha_num' => ':attribute yalnızca harfler ve sayılar içerebilir.',
    'array' => ':attribute bir dizi olmalıdır.',
    'before' => ':attribute, :date tarihinden önce olmalıdır.',
    'before_or_equal' => ':attribute, :date tarihi ile aynı veya öncesi olmalıdır.',
    'between' => [
        'numeric' => ':attribute, :min ile :max arasında olmalıdır.',
        'file' => ':attribute, :min ile :max kilobayt arasında olmalıdır.',
        'string' => ':attribute, :min ile :max karakter arasında olmalıdır.',
        'array' => ':attribute, :min ile :max öğe arasında olmalıdır.',
    ],

    'confirmed' => ':attribute doğrulaması eşleşmiyor.',
    'date' => ':attribute geçerli bir tarih olmalıdır.',
    'date_format' => ':attribute, :format biçimiyle eşleşmiyor.',
    'different' => ':attribute ve :other farklı olmalıdır.',
    'digits' => ':attribute, :digits haneli olmalıdır.',
    'digits_between' => ':attribute, :min ile :max haneli olmalıdır.',
    'dimensions' => ':attribute geçersiz resim boyutlarına sahiptir.',

    'email' => ':attribute geçerli bir e-posta adresi olmalıdır.',

    'file' => ':attribute bir dosya olmalıdır.',
    'filled' => ':attribute alanı gereklidir.',
    'image' => ':attribute bir resim olmalıdır.',

    'in_array' => ':attribute alanı, :other içinde bulunmalıdır.',
    'integer' => ':attribute bir tam sayı olmalıdır.',
    'ip' => ':attribute geçerli bir IP adresi olmalıdır.',
    'json' => ':attribute geçerli bir JSON dizesi olmalıdır.',
    'max' => [
        'numeric' => ':attribute, :max değerinden büyük olmamalıdır.',
        'file' => ':attribute, :max kilobayttan büyük olmamalıdır.',
        'string' => ':attribute, :max karakterden uzun olmamalıdır.',
        'array' => ':attribute, :max öğeden fazla içermemelidir.',
    ],
    'mimes' => ':attribute, :values türünde bir dosya olmalıdır.',
    'mimetypes' => ':attribute, :values türünde bir dosya olmalıdır.',
    'min' => [
        'numeric' => ':attribute en az :min olmalıdır.',
        'file' => ':attribute en az :min kilobayt olmalıdır.',
        'string' => ':attribute en az :min karakter olmalıdır.',
        'array' => ':attribute en az :min öğe içermelidir.',
    ],

    'numeric' => ':attribute bir sayı olmalıdır.',

    'regex' => ':attribute biçimi geçersiz.',

    'required_with_all' => ':attribute alanı, :values mevcut olduğunda gereklidir.',

    'same' => ':attribute ve :other eşleşmelidir.',
    'size' => [
        'numeric' => ':attribute, :size olmalıdır.',
        'file' => ':attribute, :size kilobayt olmalıdır.',
        'string' => ':attribute, :size karakter olmalıdır.',
        'array' => ':attribute, :size öğe içermelidir.',
    ],
    'string' => ':attribute bir metin olmalıdır.',
    'timezone' => ':attribute geçerli bir saat dilimi olmalıdır.',

    'url' => ':attribute biçimi geçersiz.',

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
        'variable_value' => ':env değişkeni',
        'invalid_password' => 'Sağlanan şifre bu hesap için geçersizdir.',
    ],
];
