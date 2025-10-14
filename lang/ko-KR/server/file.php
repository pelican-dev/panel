<?php

return [
    'title' => '파일',
    'name' => '이름',
    'size' => '크기',
    'modified_at' => '수정 시간',
    'actions' => [
        'open' => '열기',
        'download' => '다운로드',
        'copy' => [
            'title' => '복사',
            'notification' => '파일 복사됨',
        ],
        'upload' => [
            'title' => '업로드',
            'from_files' => '파일 업로드',
            'from_url' => 'URL에서 업로드',
            'url' => 'URL',
        ],
        'rename' => [
            'title' => '이름 변경',
            'file_name' => '파일 이름',
            'notification' => '파일 이름 변경됨',
        ],
        'move' => [
            'title' => '이동',
            'directory' => '디렉토리',
            'directory_hint' => '현재 디렉토리를 기준으로 새 디렉토리를 입력하세요.',
            'new_location' => '새 위치',
            'new_location_hint' => '현재 디렉토리를 기준으로 이 파일 또는 폴더의 위치를 입력하세요.',
            'notification' => '파일 이동됨',
            'bulk_notification' => ':count개의 파일이 :directory로 이동되었습니다',
        ],
        'permissions' => [
            'title' => '권한',
            'read' => '읽기',
            'write' => '쓰기',
            'execute' => '실행',
            'owner' => '소유자',
            'group' => '그룹',
            'public' => '공개',
            'notification' => '권한이 :mode로 변경되었습니다',
        ],
        'archive' => [
            'title' => '압축',
            'archive_name' => '압축 파일 이름',
            'notification' => '압축 파일 생성됨',
        ],
        'unarchive' => [
            'title' => '압축 해제',
            'notification' => '압축 해제 완료',
        ],
        'new_file' => [
            'title' => '새 파일',
            'file_name' => '새 파일 이름',
            'syntax' => '구문 강조',
            'create' => '생성',
        ],
        'new_folder' => [
            'title' => '새 폴더',
            'folder_name' => '새 폴더 이름',
        ],
        'global_search' => [
            'title' => '전역 검색',
            'search_term' => '검색어',
            'search_term_placeholder' => '검색어를 입력하세요, 예: *.txt',
            'search' => '검색',
            'search_for_term' => ':term 검색',
        ],
        'delete' => [
            'notification' => '파일 삭제됨',
            'bulk_notification' => ':count개의 파일이 삭제되었습니다',
        ],
        'edit' => [
            'title' => '편집 중: :file',
            'save_close' => '저장 및 닫기',
            'save' => '저장',
            'cancel' => '취소',
            'notification' => '파일 저장됨',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code>이(가) 너무 큽니다!',
            'body' => '최대값은 :max입니다',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code>을(를) 찾을 수 없습니다!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code>은(는) 디렉토리입니다',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code>이(가) 이미 존재합니다!',
        ],
        'files_node_error' => [
            'title' => '파일을 로드할 수 없습니다!',
        ],
        'pelicanignore' => [
            'title' => '<code>.pelicanignore</code> 파일을 편집하고 있습니다!',
            'body' => '여기에 나열된 모든 파일 또는 디렉토리는 백업에서 제외됩니다. 별표(<code>*</code>)를 사용하여 와일드카드를 지원합니다.<br>느낌표(<code>!</code>)를 앞에 붙여 이전 규칙을 무효화할 수 있습니다.',
        ],
    ],
];
