<?php

return [
    'notices' => [
        'imported' => 'Thành công thêm vào Egg này và những biến liên quan.',
        'updated_via_import' => 'Egg này đã được cập nhật sử dụng tập tin được cung cấp.',
        'deleted' => 'Thành công xóa egg được yêu cầu khỏi bảng điều khiển.',
        'updated' => 'Cấu hình cho Egg đã được cập nhật thành công.',
        'script_updated' => 'Tập lệnh để cài đặt Egg đã được cập nhật và sẽ chạy bất cứ khi nào máy chủ được cài đặt.',
        'egg_created' => 'Một quả trứng mới đã được đẻ thành công. Bạn sẽ cần phải khởi động lại những daemon đang chạy để áp dụng egg mới này.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Biến ".variable" đã được xóa và sẽ không còn khả dụng với máy chủ một khi được dựng lại.',
            'variable_updated' => 'Biến ".variable" đã được cập nhật. Bạn sẽ cần phải dựng lại những máy chủ nào sử dụng biến này để áp dụng các thay đổi.',
            'variable_created' => 'Biến mới đã được tạo thành công và được gán cho egg này.',
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
