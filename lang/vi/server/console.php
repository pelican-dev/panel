<?php

return [
    'title' => 'Cửa sổ lệnh',
    'command' => 'Nhập lệnh',
    'command_blocked' => 'Server ngoại tuyến...',
    'command_blocked_title' => 'Không thể gửi lệnh khi server ngoại tuyến',
    'open_in_admin' => 'Mở quản trị',
    'power_actions' => [
        'start' => 'Bật',
        'stop' => 'Dừng',
        'restart' => 'Khởi động lại',
        'kill' => 'Dừng ngay',
        'kill_tooltip' => 'Dừng ngay có thể khiến server bị mất dữ liệu',
    ],
    'labels' => [
        'cpu' => 'CPU',
        'memory' => 'Bộ nhớ',
        'network' => 'Mạng',
        'disk' => 'Lưu trữ',
        'name' => 'Tên',
        'status' => 'Trạng thái',
        'address' => 'Địa chỉ',
        'unavailable' => 'Không có',
    ],
    'status' => [
        'created' => 'Đã tạo',
        'starting' => 'Đang khởi động',
        'running' => 'Đang chạy',
        'restarting' => 'Đang khởi động lại',
        'exited' => 'Đã thoát',
        'paused' => 'Đã tạm dừng',
        'dead' => 'Đã văng',
        'removing' => 'Đang xóa',
        'stopping' => 'Đang dừng',
        'offline' => 'Ngoại tuyến',
        'missing' => 'Bị thiếu',
    ],
    'websocket_error' => [
        'title' => 'Không thể kết nối vào websocket',
        'body' => 'Hãy mở devtool trình duyệt.',
    ],
];
