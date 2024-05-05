<?php

return [
    'return_to_login' => '로그인으로 돌아가기',
    'failed' => '입력한 정보에 맞는 계정을 찾을 수 없습니다.',

    'login' => [
        'title' => '계속하려면 로그인하세요.',
        'button' => '로그인',
        'required' => [
            'username_or_email' => '유저명 또는 이메일을 입력해야 합니다.',
            'password' => '당신의 계정 비밀번호를 입력하세요.',
        ],
    ],

    'forgot_password' => [
        'title' => '비밀번호 재설정 요청',
        'label' => '비밀번호를 잊으셨나요?',
        'label_help' => '비밀번호 재설정에 관한 지침을 받으려면 계정의 이메일 주소를 입력해주세요.',
        'button' => '이메일 보내기',
        'required' => [
            'email' => '올바른 이메일 주소를 입력해야 합니다.',
        ],
    ],

    'reset_password' => [
        'title' => '비밀번호 재설정',
        'button' => '비밀번호 재설정',
        'new_password' => '새 비밀번호',
        'confirm_new_password' => '새 비밀번호 확인',
        'requirement' => [
            'password' => '비밀번호는 8자 이상이어야 합니다',
        ],
        'required' => [
            'password' => '새 비밀번호를 입력해야 합니다.',
            'password_confirmation' => '새 비밀번호가 일치하지 않습니다.',
        ],
        'validation' => [
            'password' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password_confirmation' => '새 비밀번호가 일치하지 않습니다.',
        ],
    ],

    'checkpoint' => [
        'title' => '2단계 인증',
        'recovery_code' => '복구 코드',
        'recovery_code_description' => '2단계 인증을 설정할 떄 받은 복구 코드중 하나를 입력해주세요.',
        'authentication_code' => '인증 코드',
        'authentication_code_description' => '기기에 생성된 2단계 인증 토큰을 입력해주세요.',
        'button' => '계속',
        'lost_device' => '기기를 잃어버렸어요',
        'have_device' => '기기를 갖고있어요',
    ],

    'two_factor' => [
        'label' => '2단계 인증 토큰',
        'label_help' => '이 계정은 2단계 인증을 필요로 합니다. 기기에 생성된 2단계 인증 토큰을 입력해주세요.',
        'checkpoint_failed' => '2단계 인증 토큰이 올바르지 않습니다.',
    ],

    'throttle' => '로그인 시도가 너무 많습니다. :seconds 초 후에 다시 시도해주세요.',
    'password_requirements' => '비밀번호는 8자 이상이어야 하고 이 사이트에만 사용되어야 합니다.',
    '2fa_must_be_enabled' => '패널을 사용하려면 계정에 2단계 인증을 활성화해야 합니다.',
];
