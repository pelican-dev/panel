<?php

return [
    'notices' => [
        'imported' => '이 Egg 및 관련 변수를 성공적으로 불러왔습니다.',
        'updated_via_import' => '이 Egg가 제공된 파일을 통해 업데이트되었습니다.',
        'deleted' => '요청한 egg을 패널에서 성공적으로 삭제했습니다.',
        'updated' => 'Egg 설정이 성공적으로 업데이트되었습니다.',
        'script_updated' => 'Egg 설치 스크립트가 업데이트되었으며 서버가 설치될 때 실행됩니다.',
        'egg_created' => 'A new egg was laid successfully. You will need to restart any running daemons to apply this new egg.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'The variable ":variable" has been deleted and will no longer be available to servers once rebuilt.',
            'variable_updated' => 'The variable ":variable" has been updated. You will need to rebuild any servers using this variable in order to apply changes.',
            'variable_created' => 'New variable has successfully been created and assigned to this egg.',
        ],
    ],
];
