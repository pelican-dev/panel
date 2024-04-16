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
    'active_url' => ':attribute geçerli bir URL değil.',
    'after' => ':attribute şu tarihten :date sonra olmalı.',
    'after_or_equal' => ':attribute, :date tarihi ile aynı veya bundan sonraki bir tarih olmalıdır.',
    'alpha' => ':attribute sadece harf içerebilir.',
    'alpha_dash' => ':attribute sadece harf, sayı ve kısa çizgi içerebilir.',
    'alpha_num' => ':attribute sadece harf ve sayı içerebilir.',
    'array' => ':attribute bir dizi olmalıdır.',
    'before' => ':attribute, :date tarihinden önceki bir tarih olmalıdır.',
    'before_or_equal' => ':attribute, :date tarihi ile aynı veya sonraki bir tarih olmalıdır.',
    'between' => [
        'numeric' => ':attribute, :min ve :max arasında olmalıdır.',
        'file' => ':attribute, :min ve :max kilobyte boyutları arasında olmalıdır.',
        'string' => ':attribute :min karakter ve :max karakter arasında olmalıdır.',
        'array' => ':attribute, :min ve :max öge arasında olmalıdır.',
    ],
    'boolean' => ':attribute sadece doğru veya yanlış olmalıdır.',
    'confirmed' => ':attribute doğrulaması uyuşmuyor.',
    'date' => ':attribute geçersiz bir tarih.',
    'date_format' => ':attribute :format formatına uymuyor.',
    'different' => ':attribute ve :other birbirinden farklı olmalıdır.',
    'digits' => ':attribute, :digits rakam olmalıdır.',
    'digits_between' => ':attribute :min ile :max arasında rakam olmalıdır.',
    'dimensions' => ':attribute geçersiz görüntü boyutlarına sahip.',
    'distinct' => ':attribute alanında tekrarlanan bir değer var.',
    'email' => ':attribute geçerli bir e-posta adresi olmalıdır.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'file' => ':attribute bir dosya olmalıdır.',
    'filled' => ':attribute alanı zorunludur.',
    'image' => ':attribute bir resim olmalıdır.',
    'in' => 'Seçilen :attribute geçersiz.',
    'in_array' => ':attribute alanı :other içinde mevcut değil.',
    'integer' => ':attribute bir tam sayı olmalıdır.',
    'ip' => ':attribute geçerli bir IP adresi olmalıdır.',
    'json' => ':attribute geçerli bir JSON dizesi olmalıdır.',
    'max' => [
        'numeric' => ':attribute, :max değerinden büyük olmayabilir.',
        'file' => ':attribute, :max kilobayttan daha büyük olmamalıdır.',
        'string' => ':attribute değeri :max karakter değerinden küçük olmalıdır.',
        'array' => ':attribute değeri :max adedinden az nesneye sahip olmamalıdır.',
    ],
    'mimes' => ':attribute, :values türünde bir dosya olmalıdır.',
    'mimetypes' => ':attribute, :values türünde bir dosya olmalıdır.',
    'min' => [
        'numeric' => ':attribute :min den küçük olmalı.',
        'file' => ':attribute, :min kilobayttan küçük olmamalıdır.',
        'string' => ':attribute en az :min karakter olmalıdır.',
        'array' => ':attribute en az :min öğeye sahip olmalıdır.',
    ],
    'not_in' => 'Seçilen :attribute geçersiz.',
    'numeric' => ':attribute bir sayı olmalıdır.',
    'present' => ':attribute alanı mevcut olmalıdır.',
    'regex' => ':attribute formatı geçersiz.',
    'required' => ':attribute alanı zorunludur.',
    'required_if' => ':other :value iken :attribute alanı gereklidir.',
    'required_unless' => ':attribute alanı, :other alanı :value değerlerinden birine sahip olmadığında zorunludur.',
    'required_with' => ':values varsa :attribute alanı zorunludur.',
    'required_with_all' => ':values varsa :attribute alanı zorunludur.',
    'required_without' => ':values mevcut değilken :attribute alanı zorunludur.',
    'required_without_all' => 'Mevcut :values değerlerinden biri olmadığında :attribute alanı zorunludur.',
    'same' => ':attribute ve :other aynı olmalı.',
    'size' => [
        'numeric' => ':attribute, :size boyutunda olmalıdır.',
        'file' => ':attribute :size kilobayt olmalıdır.',
        'string' => ': attribute en az :size karakter olmalıdır.',
        'array' => ':attribute :size nesneye sahip olmalıdır.',
    ],
    'string' => ':attribute bir dizi olmalıdır.',
    'timezone' => ':attribute geçerli bir bölge olmalıdır.',
    'unique' => ':attribute zaten alınmış.',
    'uploaded' => ':attribute yüklenemedi.',
    'url' => ':attribute formatı geçersiz.',

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
        'invalid_password' => 'Bu kullanıcı için girilen şifre hatalıdır.',
    ],
];
