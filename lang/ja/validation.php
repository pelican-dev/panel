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

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field is required.',
    'image' => 'The :attribute must be an image.',
    'in' => '選択した :attributeは 無効です。',
    'in_array' => ':attributeが:otherに存在しません。',
    'integer' => ':attribute は整数である必要があります。',
    'ip' => ':attributeは正しいIPアドレスである必要があります。',
    'json' => ':attributeは有効なJSON文字列である必要があります。',
    'max' => [
        'numeric' => ':attributeは:max以下である必要があります。',
        'file' => ':attributeは:maxキロバイト以下である必要があります。',
        'string' => ':attribute は :max 文字以下である必要があります。',
        'array' => ':attributeは:max個のアイテム以下である必要があります。',
    ],
    'mimes' => ':attributeは:valuesのファイル形式である必要があります。',
    'mimetypes' => ':attributeは:valuesのファイル形式である必要があります。',
    'min' => [
        'numeric' => ':attribute は :min 以上である必要があります。',
        'file' => ':attribute は最低 :min キロバイト以上である必要があります。',
        'string' => ':attribute は最低 :min 文字以上である必要があります。',
        'array' => ':attributeは:min個以上である必要があります。',
    ],
    'not_in' => '選択した :attributeは 無効です。',
    'numeric' => ':attributeは数字である必要があります。',
    'present' => ':attribute の項目は必ず入力する必要があります。',
    'regex' => ':attributeの形式が無効です。',
    'required' => ':attribute は必須です。',
    'required_if' => ':other の項目が :value の場合、:attribute を入力する必要があります。',
    'required_unless' => ':other の項目が :value でない場合、:attribute を入力する必要があります。',
    'required_with' => ':valuesが指定されている場合、:attributeは必須です。',
    'required_with_all' => ':valuesが指定されている場合、:attributeは必須です。',
    'required_without' => ':valuesが設定されていない場合、:attributeは必須です。',
    'required_without_all' => ':valuesが一つも存在しない場合、:attributeの項目は必須です。',
    'same' => ':attribute と :other は一致している必要があります。',
    'size' => [
        'numeric' => ':attribute のサイズは :size である必要があります。',
        'file' => ':attribute のサイズは :size キロバイトである必要があります。',
        'string' => ':attribute は :size 文字である必要があります。',
        'array' => ':attribute は :size 個である必要があります。',
    ],
    'string' => ':attribute は文字列である必要があります。',
    'timezone' => ':attributeは有効なゾーンである必要があります。',
    'unique' => ':attribute は既に使用されています。',
    'uploaded' => ':attribute のアップロードに失敗しました。',
    'url' => ':attribute の形式が無効です。',

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
        'invalid_password' => '入力されたパスワードは無効です。',
    ],
];
