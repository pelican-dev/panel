<?php

return [
    'title' => 'Panel 설치 마법사',
    'requirements' => [
        'title' => '서버 요구 사항',
        'sections' => [
            'version' => [
                'title' => 'PHP 버전',
                'or_newer' => ':version 이상',
                'content' => '현재 PHP 버전은 :version 입니다.',
            ],
            'extensions' => [
                'title' => 'PHP 확장',
                'good' => '모든 필요한 PHP 확장이 설치되었습니다.',
                'bad' => '다음 PHP 확장이 누락되었습니다: :extensions',
            ],
            'permissions' => [
                'title' => '폴더 권한',
                'good' => '모든 폴더에 올바른 권한이 설정되었습니다.',
                'bad' => '다음 폴더에 잘못된 권한이 설정되었습니다: :folders',
            ],
        ],
        'exception' => '일부 요구 사항이 누락되었습니다.',
    ],
    'environment' => [
        'title' => '환경',
        'fields' => [
            'app_name' => '앱 이름',
            'app_name_help' => '이것은 패널의 이름이 됩니다.',
            'app_url' => '앱 URL',
            'app_url_help' => '이것은 패널에 접근하는 URL이 됩니다.',
            'account' => [
                'section' => '관리자 사용자',
                'email' => 'E-Mail',
                'username' => '사용자 이름',
                'password' => '비밀번호',
            ],
        ],
    ],
    'database' => [
        'title' => '데이터베이스',
        'driver' => '데이터베이스 드라이버',
        'driver_help' => '패널 데이터베이스에 사용되는 드라이버입니다. "SQLite"를 권장합니다.',
        'fields' => [
            'host' => '데이터베이스 호스트',
            'host_help' => '데이터베이스의 호스트입니다. 접근 가능한지 확인하십시오.',
            'port' => '데이터베이스 포트',
            'port_help' => '데이터베이스의 포트입니다.',
            'path' => '데이터베이스 경로',
            'path_help' => '데이터베이스 폴더에 상대적인 .sqlite 파일의 경로입니다.',
            'name' => '데이터베이스 이름',
            'name_help' => '패널 데이터베이스의 이름입니다.',
            'username' => '데이터베이스 사용자 이름',
            'username_help' => '데이터베이스 사용자의 이름입니다.',
            'password' => '데이터베이스 비밀번호',
            'password_help' => '데이터베이스 사용자의 비밀번호입니다. 비워둘 수 있습니다.',
        ],
        'exceptions' => [
            'connection' => '데이터베이스 연결 실패',
            'migration' => '마이그레이션 실패',
        ],
    ],
    'session' => [
        'title' => '세션',
        'driver' => '세션 드라이버',
        'driver_help' => '세션 저장에 사용되는 드라이버입니다. "Filesystem" 또는 "Database"를 권장합니다.',
    ],
    'cache' => [
        'title' => '캐시',
        'driver' => '캐시 드라이버',
        'driver_help' => '캐싱에 사용되는 드라이버입니다. "Filesystem"을 권장합니다.',
        'fields' => [
            'host' => 'Redis 호스트',
            'host_help' => 'Redis 서버의 호스트입니다. 접근 가능한지 확인하십시오.',
            'port' => 'Redis 포트',
            'port_help' => 'Redis 서버의 포트입니다.',
            'username' => 'Redis 사용자 이름',
            'username_help' => 'Redis 사용자의 이름입니다. 비워둘 수 있습니다.',
            'password' => 'Redis 비밀번호',
            'password_help' => 'Redis 사용자의 비밀번호입니다. 비워둘 수 있습니다.',
        ],
        'exception' => 'Redis 연결 실패',
    ],
    'queue' => [
        'title' => '대기열',
        'driver' => '대기열 드라이버',
        'driver_help' => '대기열 처리를 위해 사용되는 드라이버입니다. "Database"를 권장합니다.',
        'fields' => [
            'done' => '아래 두 단계를 모두 완료했습니다.',
            'done_validation' => '계속하기 전에 두 단계를 모두 수행해야 합니다!',
            'crontab' => '다음 명령을 실행하여 crontab을 설정하십시오. <code>www-data</code>는 웹 서버 사용자입니다. 일부 시스템에서는 이 사용자 이름이 다를 수 있습니다!',
            'service' => '대기열 작업자 서비스를 설정하려면 다음 명령을 실행하기만 하면 됩니다.',
        ],
    ],
    'exceptions' => [
        'write_env' => '.env 파일에 쓸 수 없습니다',
        'migration' => '마이그레이션을 실행할 수 없습니다',
        'create_user' => '관리자 사용자를 생성할 수 없습니다',
    ],
    'next_step' => '다음 단계',
    'finish' => '완료',
];
