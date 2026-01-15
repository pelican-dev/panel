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

    'accepted' => 'Необходимо принять :attribute.',
    'active_url' => ':attribute не является верной ссылкой.',
    'after' => 'В поле :attribute должна быть дата после :date.',
    'after_or_equal' => 'Атрибут: должен быть датой после или равен дате.',
    'alpha' => ':attribute может содержать только буквы.',
    'alpha_dash' => 'Атрибут: может содержать только буквы, цифры и тире.',
    'alpha_num' => ':attribute может содержать только буквы и цифры.',
    'array' => ':attribute должен быть списком.',
    'before' => ':attribute должен быть датой до :date.',
    'before_or_equal' => 'В поле :attribute должна быть дата до или равняться :date.',
    'between' => [
        'numeric' => ':attribute должен быть между :min и :max.',
        'file' => ':attribute должен быть от :min до :max килобайт.',
        'string' => ':attribute должен содержать :min - :max символов.',
        'array' => ':attribute должен содержать от :min и до :max.',
    ],

    'confirmed' => ':attribute подтверждение не совпадает.',
    'date' => ':attribute не является верной датой.',
    'date_format' => 'Атрибут: не соответствует формату: формат.',
    'different' => ':attribute и :other должны быть разными.',
    'digits' => ':attribute должен содержать :digits цифр.',
    'digits_between' => ':attribute должен быть между :min и :max цифр.',
    'dimensions' => 'Поле :attribute имеет недопустимые размеры изображения.',

    'email' => 'Значение :attribute должно быть действительным адресом электронной почты.',

    'file' => ':attribute должен быть файлом.',
    'filled' => 'Поле :attribute обязательно',
    'image' => ':attribute должен быть изображением.',

    'in_array' => 'Поле :attribute не существует в :other.',
    'integer' => ':attribute должен быть целым числом.',
    'ip' => ':attribute должно быть IP-адресом.',
    'json' => 'Значение :attribute должно быть допустимой строкой JSON.',
    'max' => [
        'numeric' => ':attribute не может быть больше чем :max.',
        'file' => ':attribute не может быть больше чем :max килобайт.',
        'string' => 'Количество символов в поле :attribute не может превышать :max.',
        'array' => ':attribute не должен содержать больше :max пунктов.',
    ],
    'mimes' => ':attribute тип файла должен быть: :values.',
    'mimetypes' => ':attribute тип файла должен быть: :values.',
    'min' => [
        'numeric' => ':attribute должен быть как минимум :min.',
        'file' => ':attribute должен быть как минимум :min килобайтов.',
        'string' => ':attribute должен быть не менее :min символов.',
        'array' => ':attribute должен быть как минимум :min пунктов.',
    ],

    'numeric' => 'Атрибут : должен быть числом.',

    'regex' => 'Выбранный формат для :attribute ошибочный.',

    'required_with_all' => 'Значение :attribute обязательно, когда все из следующих значений :values существуют.',

    'same' => 'Значение :attribute должно совпадать с :other.',
    'size' => [
        'numeric' => 'Атрибут: должен быть: размер.',
        'file' => 'Поле :attribute должно быть размером в :size килобайт',
        'string' => 'Значение :attribute должно быть :size символов.',
        'array' => ':attribute должен содержать :size пунктов.',
    ],
    'string' => ':attribute должен быть строкой.',
    'timezone' => ':attribute должно быть корректным часовым поясом.',

    'url' => 'Выбранный формат для :attribute ошибочный.',

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
        'variable_value' => 'переменная :env',
        'invalid_password' => 'Введенный пароль недействителен для этой учетной записи.',
    ],
];
