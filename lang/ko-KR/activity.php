<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => '로그인에 실패했습니다.',
        'success' => '로그인 성공',
        'password-reset' => '비밀번호 재설정',
        'checkpoint' => '2단계 인증 요청됨',
        'recovery-token' => '2단계 복구 토큰 사용됨',
        'token' => '2단계 챌린지 해결됨',
        'ip-blocked' => '차단된 IP 주소에서의 요청: <b>:identifier</b>',
        'sftp' => [
            'fail' => 'SFTP 로그인 실패',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => '이메일을 <b>:old</b>에서 <b>:new</b>로 변경했습니다.',
            'password-changed' => '비밀번호를 변경했습니다.',
        ],
        'api-key' => [
            'create' => '새 API 키 <b>:identifier</b>를 생성했습니다.',
            'delete' => 'API 키 <b>:identifier</b>를 삭제했습니다.',
        ],
        'ssh-key' => [
            'create' => 'SSH 키 <b>:fingerprint</b>를 계정에 추가했습니다.',
            'delete' => 'SSH 키 <b>:fingerprint</b>를 계정에서 제거했습니다.',
        ],
        'two-factor' => [
            'create' => '2단계 인증을 활성화했습니다.',
            'delete' => '2단계 인증을 비활성화했습니다.',
        ],
    ],
    'server' => [
        'console' => [
            'command' => '서버에서 "<b>:command</b>"를 실행했습니다.',
        ],
        'power' => [
            'start' => '서버를 시작했습니다.',
            'stop' => '서버를 중지했습니다.',
            'restart' => '서버를 재시작했습니다.',
            'kill' => '서버 프로세스를 종료했습니다.',
        ],
        'backup' => [
            'download' => '백업 <b>:name</b>을 다운로드했습니다.',
            'delete' => '백업 <b>:name</b>을 삭제했습니다.',
            'restore' => '백업 <b>:name</b>을 복원했습니다 (삭제된 파일: <b>:truncate</b>)',
            'restore-complete' => '백업 <b>:name</b>의 복원을 완료했습니다.',
            'restore-failed' => '백업 <b>:name</b>의 복원을 완료하지 못했습니다.',
            'start' => '새 백업 <b>:name</b>을 시작했습니다.',
            'complete' => '백업 <b>:name</b>을 완료로 표시했습니다.',
            'fail' => '백업 <b>:name</b>을 실패로 표시했습니다.',
            'lock' => '백업 <b>:name</b>을 잠갔습니다.',
            'unlock' => '백업 <b>:name</b>의 잠금을 해제했습니다.',
            'rename' => '백업 이름을 "<b>:old_name</b>"에서 "<b>:new_name</b>"으로 변경했습니다.',
        ],
        'database' => [
            'create' => '새 데이터베이스 <b>:name</b>를 생성했습니다.',
            'rotate-password' => '데이터베이스 <b>:name</b>의 비밀번호를 변경했습니다.',
            'delete' => '데이터베이스 <b>:name</b>을 삭제했습니다.',
        ],
        'file' => [
            'compress' => '압축된 <b>:directory:files</b>|압축된 <b>:count</b> 파일이 <b>:directory</b>에 있습니다.',
            'read' => '<b>:file</b>의 내용을 보았습니다.',
            'copy' => '<b>:file</b>의 복사본을 만들었습니다.',
            'create-directory' => '<b>:directory:name</b> 디렉토리를 만들었습니다.',
            'decompress' => '<b>:file</b>의 압축을 해제했습니다.',
            'delete' => '<b>:directory:files</b>를 삭제했습니다.|<b>:directory</b>에서 <b>:count</b>개의 파일을 삭제했습니다.',
            'download' => '<b>:file</b>을 다운로드했습니다.',
            'pull' => '<b>:url</b>에서 <b>:directory</b>로 원격 파일을 다운로드했습니다.',
            'rename' => '<b>:from</b>을 <b>:to</b>로 이동/이름 변경했습니다.|<b>:directory</b>에서 <b>:count</b>개의 파일을 이동/이름 변경했습니다.',
            'write' => '<b>:file</b>에 새 내용을 작성했습니다.',
            'upload' => '파일 업로드를 시작했습니다.',
            'uploaded' => '<b>:directory:file</b>을 업로드했습니다.',
        ],
        'sftp' => [
            'denied' => '권한으로 인해 SFTP 액세스가 차단되었습니다.',
            'create' => '<b>:files</b>를 생성했습니다.|<b>:count</b>개의 새 파일을 생성했습니다.',
            'write' => '<b>:files</b>의 내용을 수정했습니다.|<b>:count</b>개의 파일 내용을 수정했습니다.',
            'delete' => '<b>:files</b>를 삭제했습니다.|<b>:count</b>개의 파일을 삭제했습니다.',
            'create-directory' => '<b>:files</b> 디렉토리를 생성했습니다.|<b>:count</b>개의 디렉토리를 생성했습니다.',
            'rename' => '<b>:from</b>을 <b>:to</b>로 이름을 변경했습니다.|<b>:count</b>개의 파일을 이동/이름 변경했습니다.',
        ],
        'allocation' => [
            'create' => '서버에 <b>:allocation</b>을 추가했습니다.',
            'notes' => '<b>:allocation</b>의 메모를 "<b>:old</b>"에서 "<b>:new</b>"로 업데이트했습니다.',
            'primary' => '<b>:allocation</b>을 기본 서버 할당으로 설정했습니다.',
            'delete' => '<b>:allocation</b> 할당을 삭제했습니다.',
        ],
        'schedule' => [
            'create' => '<b>:name</b> 스케줄을 생성했습니다.',
            'update' => '<b>:name</b> 스케줄을 업데이트했습니다.',
            'execute' => '<b>:name</b> 스케줄을 수동으로 실행했습니다.',
            'delete' => '<b>:name</b> 스케줄을 삭제했습니다.',
        ],
        'task' => [
            'create' => '<b>:name</b> 스케줄에 대한 새로운 "<b>:action</b>" 작업을 생성했습니다.',
            'update' => '<b>:name</b> 스케줄의 "<b>:action</b>" 작업을 업데이트했습니다.',
            'delete' => '<b>:name</b> 스케줄의 "<b>:action</b>" 작업을 삭제했습니다.',
        ],
        'settings' => [
            'rename' => '서버 이름을 "<b>:old</b>"에서 "<b>:new</b>"로 변경했습니다.',
            'description' => '서버 설명을 "<b>:old</b>"에서 "<b>:new</b>"로 변경했습니다.',
            'reinstall' => '서버를 재설치했습니다.',
        ],
        'startup' => [
            'edit' => '<b>:variable</b> 변수를 "<b>:old</b>"에서 "<b>:new</b>"로 변경했습니다.',
            'image' => '서버의 Docker 이미지를 <b>:old</b>에서 <b>:new</b>로 업데이트했습니다.',
            'command' => '서버의 시작 명령을 <b>:old</b>에서 <b>:new</b>로 업데이트했습니다.',
        ],
        'subuser' => [
            'create' => '<b>:email</b>을 하위 사용자로 추가했습니다.',
            'update' => '<b>:email</b>의 하위 사용자 권한을 업데이트했습니다.',
            'delete' => '<b>:email</b>을 하위 사용자에서 제거했습니다.',
        ],
        'crashed' => '서버가 충돌했습니다.',
    ],
];
