<?php

return [
    'title' => '상태',
    'results_refreshed' => '상태 검사 결과가 업데이트되었습니다.',
    'checked' => ':time의 검사 결과',
    'refresh' => '새로 고침',
    'results' => [
        'cache' => [
            'label' => '캐시',
            'ok' => '완료',
            'failed_retrieve' => '애플리케이션 캐시 값을 설정하거나 검색할 수 없습니다.',
            'failed' => '애플리케이션 캐시에서 예외가 발생했습니다: :error',
        ],
        'database' => [
            'label' => '데이터베이스',
            'ok' => '완료',
            'failed' => '데이터베이스에 연결할 수 없습니다: :error',
        ],
        'debugmode' => [
            'label' => '디버그 모드',
            'ok' => '디버그 모드가 비활성화되었습니다.',
            'failed' => '디버그 모드는 :expected로 설정되어야 했지만 실제로는 :actual로 설정되었습니다.',
        ],
        'environment' => [
            'label' => '환경',
            'ok' => 'Ok, :actual로 설정됨',
            'failed' => '환경이 :actual로 설정되어 있으며, :expected로 설정되어야 했습니다.',
        ],
        'nodeversions' => [
            'label' => '노드 버전',
            'ok' => '노드가 최신 상태입니다',
            'failed' => ':outdated/:all 노드가 구식입니다',
            'no_nodes_created' => '노드가 생성되지 않았습니다',
            'no_nodes' => '노드가 없습니다',
            'all_up_to_date' => '모두 최신 상태입니다',
            'outdated' => ':outdated/:all 구식',
        ],
        'panelversion' => [
            'label' => '패널 버전',
            'ok' => '패널이 최신 상태입니다',
            'failed' => '설치된 버전은 :currentVersion이지만 최신 버전은 :latestVersion입니다',
            'up_to_date' => '최신 상태',
            'outdated' => '구식',
        ],
        'schedule' => [
            'label' => '일정',
            'ok' => '완료',
            'failed_last_ran' => '일정의 마지막 실행은 :time 분 이상 지났습니다',
            'failed_not_ran' => '일정이 아직 실행되지 않았습니다.',
        ],
        'useddiskspace' => [
            'label' => '디스크 공간',
        ],
    ],
    'checks' => [
        'successful' => '성공',
        'failed' => '실패 :checks',
    ],
];
