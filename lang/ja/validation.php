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

    'accepted' => ':attributeを承認してください。',
    'active_url' => ':attributeは、有効なURLではありません。',
    'after' => ':attributeには、:dateより後の日付を指定してください。',
    'after_or_equal' => ':attribute は :date と同じ日付かそれ以降でなければいけません。',
    'alpha' => ':attributeには、アルファベッドのみ使用できます。',
    'alpha_dash' => ':attributeには、英数字(\'A-Z\',\'a-z\',\'0-9\')とハイフン(-)が使用できます。',
    'alpha_num' => ':attributeには、英数字(\'A-Z\',\'a-z\',\'0-9\')が使用できます。',
    'array' => ':attributeには、配列を指定してください。',
    'before' => ':attribute は :date よりも前の日付である必要があります。',
    'before_or_equal' => ':attributeには、:date以前の日付を指定してください。',
    'between' => [
        'numeric' => ':attribute は :min から :max キロバイトである必要があります。',
        'file' => ':attributeには、:min KBから:max KBまでのサイズのファイルを指定してください。',
        'string' => ':attributeは、:min文字から:max文字にしてください。',
        'array' => ':attributeの項目は、:min個から:max個にしてください。',
    ],
    'boolean' => ':attribute はtrueかfalseである必要があります。',
    'confirmed' => ':attribute の確認が一致しません。',
    'date' => ':attribute が正しい日付ではありません。',
    'date_format' => ':attribute は :format のフォーマットと一致しません。',
    'different' => ':attributeと:otherは異なる必要があります。',
    'digits' => ':attributeは:digits桁である必要があります。',
    'digits_between' => ':attribute は :min から :max 桁である必要があります。',
    'dimensions' => ':attribute は無効な画像サイズです。',
    'distinct' => ':attributeの値が重複しています。',
    'email' => ':attribute はメールアドレスの形式ではありません。',
    'exists' => '選択した :attributeは 無効です。',
    'file' => ':attribute はファイルである必要があります。',
    'filled' => ':attribute の項目は必ず入力する必要があります。',
    'image' => ':attribute は画像である必要があります。',
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
