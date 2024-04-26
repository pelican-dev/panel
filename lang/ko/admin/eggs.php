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
    'descriptions' => [
        'name' => 'A simple, human-readable name to use as an identifier for this Egg.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
