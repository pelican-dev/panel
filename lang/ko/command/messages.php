<?php

return [
    'user' => [
        'search_users' => '사용자 이름, 사용자 ID 또는 이메일 주소를 입력하세요',
        'select_search_user' => '삭제할 사용자 ID (재검색하려면 \'0\' 입력)',
        'deleted' => '패널에서 사용자가 성공적으로 삭제되었습니다.',
        'confirm_delete' => '패널에서 이 사용자를 삭제하시겠습니까?',
        'no_users_found' => '제공된 검색어에 대한 사용자를 찾을 수 없습니다.',
        'multiple_found' => '제공된 사용자에 대해 여러 계정이 발견되었습니다. --no-interaction 플래그로 인해 사용자를 삭제할 수 없습니다.',
        'ask_admin' => '이 사용자는 관리자입니까?',
        'ask_email' => '이메일 주소',
        'ask_username' => '사용자 이름',
        'ask_password' => '비밀번호',
        'ask_password_tip' => '사용자에게 이메일로 전송되는 무작위 비밀번호로 계정을 생성하려면 이 명령을 다시 실행(CTRL+C)하고 `--no-password` 플래그를 전달하세요.',
        'ask_password_help' => '비밀번호는 최소 8자 이상이어야 하며 대문자와 숫자를 하나 이상 포함해야 합니다.',
        '2fa_help_text' => '이 명령은 활성화된 경우 사용자 계정의 2단계 인증을 비활성화합니다. 사용자가 계정에서 잠긴 경우에만 계정 복구 명령으로 사용해야 합니다. 이것이 원하는 작업이 아닌 경우 CTRL+C를 눌러 프로세스를 종료하세요.',
        '2fa_disabled' => ':email에 대한 2단계 인증이 비활성화되었습니다.',
    ],
    'schedule' => [
        'output_line' => '`:schedule` (:id)의 첫 번째 작업에 대한 작업을 디스패치합니다.',
    ],
    'maintenance' => [
        'deleting_service_backup' => '서비스 백업 파일 :file을 삭제합니다.',
    ],
    'server' => [
        'rebuild_failed' => '노드 ":node"의 ":name" (#:id)에 대한 재구축 요청이 오류와 함께 실패했습니다: :message',
        'reinstall' => [
            'failed' => '노드 ":node"의 ":name" (#:id)에 대한 재설치 요청이 오류와 함께 실패했습니다: :message',
            'confirm' => '서버 그룹에 대해 재설치를 수행하려고 합니다. 계속하시겠습니까?',
        ],
        'power' => [
            'confirm' => ':count개의 서버에 대해 :action을 수행하려고 합니다. 계속하시겠습니까?',
            'action_failed' => '노드 ":node"의 ":name" (#:id)에 대한 전원 작업 요청이 오류와 함께 실패했습니다: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP 호스트 (예: smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP 포트',
            'ask_smtp_username' => 'SMTP 사용자 이름',
            'ask_smtp_password' => 'SMTP 비밀번호',
            'ask_mailgun_domain' => 'Mailgun 도메인',
            'ask_mailgun_endpoint' => 'Mailgun 엔드포인트',
            'ask_mailgun_secret' => 'Mailgun 시크릿',
            'ask_mandrill_secret' => 'Mandrill 시크릿',
            'ask_postmark_username' => 'Postmark API 키',
            'ask_driver' => '이메일 전송에 사용할 드라이버는 무엇입니까?',
            'ask_mail_from' => '이메일이 발신될 이메일 주소',
            'ask_mail_name' => '이메일에 표시될 이름',
            'ask_encryption' => '사용할 암호화 방법',
        ],
    ],
];
