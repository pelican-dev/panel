<?php

return [
    'exceptions' => [
        'user_has_servers' => 'Không thể xóa một người dùng có máy chủ đang hoạt động có gắn kết với tài khoản của họ. Xin hãy xóa máy chủ của họ trước khi tiếp tục.',
        'user_is_self' => 'Không thể xóa tài khoản người dùng của riêng bạn.',
    ],
    'notices' => [
        'account_created' => 'Tài khoản đã được tạo thành công.',
        'account_updated' => 'Tài khoản đã được cập nhật thành công.',
    ],
    'last_admin' => [
        'hint' => 'This is the last root administrator!',
        'helper_text' => 'You must have at least one root administrator in your system.',
    ],
    'root_admin' => 'Administrator (Root)',
    'language' => [
        'helper_text1' => 'Your language (:state) has not been translated yet!\nBut never fear, you can help fix that by',
        'helper_text2' => 'contributing directly here',
    ],
];
