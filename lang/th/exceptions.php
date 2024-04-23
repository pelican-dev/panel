<?php

return [
    'daemon_connection_failed' => 'เกิดข้อผิดพลาด HTTP/:code ระหว่างการพยายามสื่อสารกับโปรแกรเดมอน ข้อผิดพลาดนี้ถูกบันทึกแล้ว',
    'node' => [
        'servers_attached' => 'Node ต้องไม่ผูกอยู่กับเซิฟเวอร์ถึงจะทำการลบได้',
        'daemon_off_config_updated' => 'การตั้งค่าเดมอนได้<strong>ถูกบันทึกแล้ว</strong> แต่ได้เกิดข้อผิดพลาดขึ้นระหว่างการพยายามปรับปรุงไฟล์ตั้งค่าของเดม่อน ท่านต้องแก้ไขไฟล์ตั้งค่า (config.yml) ของเดม่อนด้วยตัวเองเพื่อให้การตั้งค่าเป็นผล',
    ],
    'allocations' => [
        'server_using' => 'พอร์ตนี้กำลังมีเซิฟเวอร์ใช้งานอยู่ จะสามารถลบพอร์ตได้ก็ต่อเมื่อไม่มีเซิฟเวอร์ผูกอยู่เท่านั้น',
        'too_many_ports' => 'ไม่สามารถการเพิ่มพอร์ตมากกว่า 1000 พอร์ตในครั้งเดียวได้',
        'invalid_mapping' => 'กำหนดสัดส่วนพอร์ตไม่ถูกต้อง ไม่สามารถดำเนินการได้',
        'cidr_out_of_range' => 'สัญกรณ์ CIDR อนุญาติให้ใช้มาร์สได้ระหว่าง /25 ถึง /32 เท่านั้น',
        'port_out_of_range' => 'พอร์ตต้องมีค่ามากกว่า 1024 และน้อยกว่าหรือเท่ากับ 65535',
    ],
    'egg' => [
        'delete_has_servers' => 'An Egg with active servers attached to it cannot be deleted from the Panel.',
        'invalid_copy_id' => 'The Egg selected for copying a script from either does not exist, or is copying a script itself.',
        'has_children' => 'This Egg is a parent to one or more other Eggs. Please delete those Eggs before deleting this Egg.',
    ],
    'variables' => [
        'env_not_unique' => 'The environment variable :name must be unique to this Egg.',
        'reserved_name' => 'The environment variable :name is protected and cannot be assigned to a variable.',
        'bad_validation_rule' => 'The validation rule ":rule" is not a valid rule for this application.',
    ],
    'importer' => [
        'json_error' => 'There was an error while attempting to parse the JSON file: :error.',
        'file_error' => 'The JSON file provided was not valid.',
        'invalid_json_provided' => 'The JSON file provided is not in a format that can be recognized.',
    ],
    'subusers' => [
        'editing_self' => 'Editing your own subuser account is not permitted.',
        'user_is_owner' => 'You cannot add the server owner as a subuser for this server.',
        'subuser_exists' => 'A user with that email address is already assigned as a subuser for this server.',
    ],
    'databases' => [
        'delete_has_databases' => 'Cannot delete a database host server that has active databases linked to it.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'The maximum interval time for a chained task is 15 minutes.',
    ],
    'locations' => [
        'has_nodes' => 'Cannot delete a location that has active nodes attached to it.',
    ],
    'users' => [
        'node_revocation_failed' => 'Failed to revoke keys on <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'No nodes satisfying the requirements specified for automatic deployment could be found.',
        'no_viable_allocations' => 'No allocations satisfying the requirements for automatic deployment were found.',
    ],
    'api' => [
        'resource_not_found' => 'The requested resource does not exist on this server.',
    ],
];
