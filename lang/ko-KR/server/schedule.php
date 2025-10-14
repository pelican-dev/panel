<?php

return [
    'title' => '스케줄',
    'new' => '새 스케줄',
    'edit' => '스케줄 편집',
    'save' => '스케줄 저장',
    'delete' => '스케줄 삭제',
    'import' => '스케줄 가져오기',
    'export' => '스케줄 내보내기',
    'name' => '이름',
    'cron' => 'Cron',
    'status' => '상태',
    'schedule_status' => [
        'inactive' => '비활성',
        'processing' => '처리 중',
        'active' => '활성',
    ],
    'no_tasks' => '작업 없음',
    'run_now' => '지금 실행',
    'online_only' => '온라인일 때만',
    'last_run' => '마지막 실행',
    'next_run' => '다음 실행',
    'never' => '없음',
    'cancel' => '취소',

    'only_online' => '서버가 온라인일 때만?',
    'only_online_hint' => '서버가 실행 중인 상태일 때만 이 스케줄을 실행합니다.',
    'enabled' => '스케줄 활성화?',
    'enabled_hint' => '활성화하면 이 스케줄이 자동으로 실행됩니다.',

    'cron_body' => '아래의 cron 입력은 항상 UTC를 기준으로 한다는 점을 유념하세요.',
    'cron_timezone' => '사용자 시간대(:timezone)의 다음 실행: <b> :next_run </b>',

    'invalid' => '유효하지 않음',

    'time' => [
        'minute' => '분',
        'hour' => '시간',
        'day' => '일',
        'week' => '주',
        'month' => '월',
        'day_of_month' => '월의 일',
        'day_of_week' => '주의 일',

        'hourly' => '매시간',
        'daily' => '매일',
        'weekly_mon' => '매주 (월요일)',
        'weekly_sun' => '매주 (일요일)',
        'monthly' => '매월',
        'every_min' => 'x분마다',
        'every_hour' => 'x시간마다',
        'every_day' => 'x일마다',
        'every_week' => 'x주마다',
        'every_month' => 'x개월마다',
        'every_day_of_week' => '주의 x번째 요일마다',

        'every' => '매',
        'minutes' => '분',
        'hours' => '시간',
        'days' => '일',
        'months' => '개월',

        'monday' => '월요일',
        'tuesday' => '화요일',
        'wednesday' => '수요일',
        'thursday' => '목요일',
        'friday' => '금요일',
        'saturday' => '토요일',
        'sunday' => '일요일',
    ],

    'tasks' => [
        'title' => '작업',
        'create' => '작업 생성',
        'limit' => '작업 제한에 도달했습니다',
        'action' => '작업',
        'payload' => '페이로드',
        'time_offset' => '시간 오프셋',
        'first_task' => '첫 번째 작업',
        'seconds' => '초',
        'continue_on_failure' => '실패 시 계속',

        'actions' => [
            'title' => '작업',
            'power' => [
                'title' => '전원 작업 전송',
                'action' => '전원 작업',
                'start' => '시작',
                'stop' => '중지',
                'restart' => '재시작',
                'kill' => '강제 종료',
            ],
            'command' => [
                'title' => '명령어 전송',
                'command' => '명령어',
            ],
            'backup' => [
                'title' => '백업 생성',
                'files_to_ignore' => '제외할 파일',
            ],
            'delete' => [
                'title' => '파일 삭제',
                'files_to_delete' => '삭제할 파일',

            ],
        ],
    ],

    'notification_invalid_cron' => '제공된 cron 데이터가 유효한 표현식으로 평가되지 않습니다',

    'import_action' => [
        'file' => '파일',
        'url' => 'URL',
        'schedule_help' => '원본 .json 파일이어야 합니다 (예: schedule-daily-restart.json)',
        'url_help' => 'URL은 원본 .json 파일을 직접 가리켜야 합니다',
        'add_url' => '새 URL',
        'import_failed' => '가져오기 실패',
        'import_success' => '가져오기 성공',
    ],
];
