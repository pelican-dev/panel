<?php

return [
    'nav_title' => '데이터베이스 호스트',
    'model_label' => '데이터베이스 호스트',
    'model_label_plural' => '데이터베이스 호스트',
    'table' => [
        'database' => '데이터베이스',
        'name' => '이름',
        'host' => '호스트',
        'port' => '포트',
        'name_helper' => '이름을 비워두면 무작위 이름이 자동 생성됩니다.',
        'username' => '사용자 이름',
        'password' => '비밀번호',
        'remote' => '연결 허용',
        'remote_helper' => '연결을 허용해야 하는 위치입니다. 비워두면 모든 위치에서의 연결이 허용됩니다.',
        'max_connections' => '최대 연결 수',
        'created_at' => '생성됨',
        'connection_string' => 'JDBC 연결 문자열',
    ],
    'error' => '호스트에 연결하는 중 오류 발생',
    'host' => '호스트',
    'host_help' => '이 패널에서 이 MySQL 호스트에 연결하여 새 데이터베이스를 생성할 때 사용해야 하는 IP 주소 또는 도메인 이름입니다.',
    'port' => '포트',
    'port_help' => '이 호스트에서 MySQL이 실행 중인 포트입니다.',
    'max_database' => '최대 데이터베이스',
    'max_databases_help' => '이 호스트에서 생성할 수 있는 최대 데이터베이스 수입니다. 한도가 초과되면 이 호스트에서 새 데이터베이스를 생성할 수 없습니다. 비워두면 무제한입니다.',
    'display_name' => '표시 이름',
    'display_name_help' => '최종 사용자에게 표시되어야 하는 IP 주소 또는 도메인 이름입니다.',
    'username' => '사용자 이름',
    'username_help' => '시스템에서 새 사용자 및 데이터베이스를 생성할 수 있는 충분한 권한이 있는 계정의 사용자 이름입니다.',
    'password' => '비밀번호',
    'password_help' => '데이터베이스 사용자의 비밀번호입니다.',
    'linked_nodes' => '연결된 노드',
    'linked_nodes_help' => '이 설정은 선택한 노드의 서버에 데이터베이스를 추가할 때만 이 데이터베이스 호스트에 기본값으로 설정됩니다.',
    'connection_error' => '데이터베이스 호스트에 연결하는 중 오류 발생',
    'no_database_hosts' => '데이터베이스 호스트가 없습니다.',
    'no_nodes' => '노드가 없습니다.',
    'delete_help' => '데이터베이스 호스트에 데이터베이스가 있습니다.',
    'unlimited' => '무제한',
    'anywhere' => '어디서나',

    'rotate' => '비밀번호 변경',
    'rotate_password' => '비밀번호 변경',
    'rotated' => '비밀번호가 변경되었습니다.',
    'rotate_error' => '비밀번호 변경 실패',
    'databases' => '데이터베이스',

    'setup' => [
        'preparations' => '준비 작업',
        'database_setup' => '데이터베이스 설정',
        'panel_setup' => '패널 설정',

        'note' => '현재 데이터베이스 호스트에 대해 MySQL/ MariaDB 데이터베이스만 지원됩니다!',
        'different_server' => '패널과 데이터베이스가 <i>다른</i> 서버에 있습니까?',

        'database_user' => '데이터베이스 사용자',
        'cli_login' => '<code>mysql -u root -p</code>를 사용하여 mysql cli에 접속하세요.',
        'command_create_user' => '사용자 생성 명령',
        'command_assign_permissions' => '권한 할당 명령',
        'cli_exit' => 'mysql cli를 종료하려면 <code>exit</code>를 실행하세요.',
        'external_access' => '외부 접근',
        'allow_external_access' => '
                        <p>서버가 연결할 수 있도록 이 MySQL 인스턴스에 대한 외부 접근을 허용해야 할 수 있습니다.</p>
                        <br>
                        <p>이를 위해 <code>my.cnf</code>를 열어야 합니다. 이 파일의 위치는 OS 및 MySQL 설치 방법에 따라 다릅니다. <code>find /etc -iname my.cnf</code>를 입력하여 찾을 수 있습니다.</p>
                        <br>
                        <p><code>my.cnf</code>를 열고 아래 텍스트를 파일 하단에 추가한 후 저장하세요:<br>
                        <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                        <br>
                        <p>변경 사항을 적용하려면 MySQL/ MariaDB를 재시작하세요. 이렇게 하면 기본적으로 localhost의 요청만 수락하는 기본 MySQL 구성이 재정의됩니다. 이를 업데이트하면 모든 인터페이스에서의 연결, 즉 외부 연결이 허용됩니다. 방화벽에서 MySQL 포트(기본값 3306)를 허용해야 합니다.</p>
                    ',
    ],
];
