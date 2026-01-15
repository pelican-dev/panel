<?php

return [
    'title' => '백업',
    'empty' => '백업 없음',
    'size' => '크기',
    'created_at' => '생성 시간',
    'status' => '상태',
    'is_locked' => '잠금 상태',
    'backup_status' => [
        'in_progress' => '진행 중',
        'successful' => '성공',
        'failed' => '실패',
    ],
    'actions' => [
        'create' => [
            'title' => '백업 생성',
            'limit' => '백업 제한에 도달했습니다',
            'created' => ':name 생성됨',
            'notification_success' => '백업이 성공적으로 생성되었습니다',
            'notification_fail' => '백업 생성 실패',
            'name' => '이름',
            'ignored' => '제외할 파일 및 디렉토리',
            'locked' => '잠금?',
            'lock_helper' => '명시적으로 잠금을 해제할 때까지 이 백업이 삭제되지 않도록 방지합니다.',
        ],
        'lock' => [
            'lock' => '잠금',
            'unlock' => '잠금 해제',
        ],
        'download' => '다운로드',
        'rename' => [
            'title' => '이름 변경',
            'new_name' => '백업 이름',
            'notification_success' => '백업 이름이 성공적으로 변경되었습니다',
        ],
        'restore' => [
            'title' => '복원',
            'helper' => '서버가 중지됩니다. 이 프로세스가 완료될 때까지 전원 상태를 제어하거나 파일 관리자에 액세스하거나 추가 백업을 생성할 수 없습니다.',
            'delete_all' => '백업을 복원하기 전에 모든 파일을 삭제하시겠습니까?',
            'notification_started' => '백업 복원 중',
            'notification_success' => '백업이 성공적으로 복원되었습니다',
            'notification_fail' => '백업 복원 실패',
            'notification_fail_body_1' => '이 서버는 현재 백업을 복원할 수 있는 상태가 아닙니다.',
            'notification_fail_body_2' => '이 백업은 현재 복원할 수 없습니다: 완료되지 않았거나 실패했습니다.',
        ],
        'delete' => [
            'title' => '백업 삭제',
            'description' => ':backup을 삭제하시겠습니까?',
            'notification_success' => '백업 삭제됨',
            'notification_fail' => '백업을 삭제할 수 없습니다',
            'notification_fail_body' => '노드에 연결하지 못했습니다. 다시 시도하세요.',
        ],
    ],
];
