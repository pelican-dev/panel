<?php

return [
    'title' => 'Sao lưu',
    'empty' => 'Không có sao lưu',
    'size' => 'Kích cỡ',
    'created_at' => 'Tạo lúc',
    'status' => 'Trạng thái',
    'is_locked' => 'Bị khoá',
    'backup_status' => [
        'in_progress' => 'Đang thực hiện',
        'successful' => 'Thành công',
        'failed' => 'Thất bại',
    ],
    'actions' => [
        'create' => [
            'title' => 'Tạo sao lưu',
            'limit' => 'Đã đạt đến giới hạn sao lưu',
            'created' => 'Đã tạo :name',
            'notification_success' => 'Đã tạo sao lưu thành công',
            'notification_fail' => 'Tạo sao lưu thất bại',
            'name' => 'Tên',
            'ignored' => 'Các tệp không được sao luu',
            'locked' => 'Khoá?',
            'lock_helper' => 'Tránh cho tệp sao lưu này không bị xoá đến khi được mở khoá.',
        ],
        'lock' => [
            'lock' => 'Khoá',
            'unlock' => 'Mở khoá',
        ],
        'download' => 'Tải xuống',
        'rename' => [
            'title' => 'Đổi tên',
            'new_name' => 'Tên sao lưu',
            'notification_success' => 'Đã đổi tên sao lưu',
        ],
        'restore' => [
            'title' => 'Khôi phục',
            'helper' => 'Máy chủ sẽ bị dừng, Khi đó bạn không thể tắt bật, truy cập tệp, hay tạo thêm sao lưu đến khi thực hiện xong.',
            'delete_all' => 'Xoá tất cả tệp khi khôi phục sao lưu?',
            'notification_started' => 'Đang khôi phục bản sao lưu',
            'notification_success' => 'Đã khôi phục sao lưu thành công',
            'notification_fail' => 'Khôi phục sao lưu thất bại',
            'notification_fail_body_1' => 'Máy chủ này hiện không thể tạo sao lưu.',
            'notification_fail_body_2' => 'Máy chủ này không thể tạo sao lưu: sao lưu chưa thành công hoặc thất bại.',
        ],
        'delete' => [
            'title' => 'Xoá sao lưu',
            'description' => 'Bạn có muốn xoá :backup?',
            'notification_success' => 'Đã xoá sao lưu',
            'notification_fail' => 'Không thể xoá sao lưu',
            'notification_fail_body' => 'Không thể kết nối đến node.',
        ],
    ],
];
