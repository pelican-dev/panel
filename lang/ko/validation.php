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

    'accepted' => ':attribute을(를) 동의해야 합니다.',
    'active_url' => ':attribute은(는) 유효한 URL이 아닙니다.',
    'after' => ':attribute은(는) :date 이후의 날짜여야 합니다.',
    'after_or_equal' => ':attribute은(는) :date 이후 또는 같은 날짜여야 합니다.',
    'alpha' => ':attribute은(는) 문자만 포함할 수 있습니다.',
    'alpha_dash' => ':attribute은(는) 문자, 숫자 및 대시만 포함할 수 있습니다.',
    'alpha_num' => ':attribute은(는) 문자와 숫자만 포함할 수 있습니다.',
    'array' => ':attribute은(는) 배열이어야 합니다.',
    'before' => ':attribute은(는) :date 이전의 날짜여야 합니다.',
    'before_or_equal' => ':attribute은(는) :date 이전 또는 같은 날짜여야 합니다.',
    'between' => [
        'numeric' => ':attribute은(는) :min과(와) :max 사이여야 합니다.',
        'file' => ':attribute은(는) :min과(와) :max 킬로바이트 사이여야 합니다.',
        'string' => ':attribute은(는) :min과(와) :max 문자 사이여야 합니다.',
        'array' => ':attribute은(는) :min과(와) :max 항목을 포함해야 합니다.',
    ],

    'confirmed' => ':attribute 확인이 일치하지 않습니다.',
    'date' => ':attribute은(는) 유효한 날짜가 아닙니다.',
    'date_format' => ':attribute은(는) :format 형식과 일치하지 않습니다.',
    'different' => ':attribute과(와) :other은(는) 달라야 합니다.',
    'digits' => ':attribute은(는) :digits 자리 숫자여야 합니다.',
    'digits_between' => ':attribute은(는) :min과(와) :max 자리 숫자여야 합니다.',
    'dimensions' => ':attribute의 이미지 크기가 유효하지 않습니다.',

    'email' => ':attribute은(는) 유효한 이메일 주소여야 합니다.',

    'file' => ':attribute은(는) 파일이어야 합니다.',
    'filled' => ':attribute 필드는 필수입니다.',
    'image' => ':attribute은(는) 이미지여야 합니다.',

    'in_array' => ':attribute 필드는 :other에 존재하지 않습니다.',
    'integer' => ':attribute은(는) 정수여야 합니다.',
    'ip' => ':attribute은(는) 유효한 IP 주소여야 합니다.',
    'json' => ':attribute은(는) 유효한 JSON 문자열이어야 합니다.',
    'max' => [
        'numeric' => ':attribute은(는) :max보다 클 수 없습니다.',
        'file' => ':attribute은(는) :max 킬로바이트보다 클 수 없습니다.',
        'string' => ':attribute은(는) :max 문자보다 클 수 없습니다.',
        'array' => ':attribute은(는) :max 항목을 포함할 수 없습니다.',
    ],
    'mimes' => ':attribute은(는) 다음 유형의 파일이어야 합니다: :values.',
    'mimetypes' => ':attribute은(는) 다음 유형의 파일이어야 합니다: :values.',
    'min' => [
        'numeric' => ':attribute은(는) 최소 :min이어야 합니다.',
        'file' => ':attribute은(는) 최소 :min 킬로바이트이어야 합니다.',
        'string' => ':attribute은(는) 최소 :min 문자이어야 합니다.',
        'array' => ':attribute은(는) 최소 :min 항목을 포함해야 합니다.',
    ],

    'numeric' => ':attribute은(는) 숫자여야 합니다.',

    'regex' => ':attribute 형식이 유효하지 않습니다.',

    'required_with_all' => ':attribute 필드는 :values가 존재할 때 필수입니다.',

    'same' => ':attribute과(와) :other은(는) 일치해야 합니다.',
    'size' => [
        'numeric' => ':attribute은(는) :size이어야 합니다.',
        'file' => ':attribute은(는) :size 킬로바이트이어야 합니다.',
        'string' => ':attribute은(는) :size 문자이어야 합니다.',
        'array' => ':attribute은(는) :size 항목을 포함해야 합니다.',
    ],
    'string' => ':attribute은(는) 문자열이어야 합니다.',
    'timezone' => ':attribute은(는) 유효한 시간대이어야 합니다.',

    'url' => ':attribute 형식이 유효하지 않습니다.',

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
        'variable_value' => ':env 변수',
        'invalid_password' => '제공된 비밀번호가 이 계정에 대해 유효하지 않습니다.',
    ],
];
