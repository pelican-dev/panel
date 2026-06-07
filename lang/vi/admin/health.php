<?php

return [
    'title' => 'Sức khoẻ',
    'results_refreshed' => 'Kết quả kiểm tra sức khỏe đã được cập nhật',
    'checked' => 'Đã kiểm tra kết quả lúc :time',
    'refresh' => 'Làm mới',
    'results' => [
        'cache' => [
            'label' => 'Bộ nhớ đệm',
            'ok' => 'Đồng ý',
            'failed_retrieve' => 'Không thể thiết lập hoặc truy xuất giá trị bộ nhớ đệm của ứng dụng.',
            'failed' => 'Đã xảy ra ngoại lệ với bộ nhớ đệm ứng dụng: :error',
        ],
        'database' => [
            'label' => 'Cơ sở dữ liệu',
            'ok' => 'Đồng ý',
            'failed' => 'Không thể kết nối đến cơ sở dữ liệu: :error',
        ],
        'debugmode' => [
            'label' => 'Chế độ gỡ lỗi',
            'ok' => 'Đã tắt chế độ gỡ lỗi',
            'failed' => 'Chế độ gỡ lỗi được mong đợi là :expected, nhưng thực tế là :actual',
        ],
        'environment' => [
            'label' => 'Môi trường',
            'ok' => 'Đồng ý, đặt thành :actual',
            'failed' => 'Môi trường được đặt thành :actual , Dự kiến :expected',
        ],
        'nodeversions' => [
            'label' => 'Phiên bản Node',
            'ok' => 'Node đã được cập nhật',
            'failed' => ':outdated/:all Node cần được cập nhật',
            'no_nodes_created' => 'Không có Node nào được tạo',
            'no_nodes' => 'Không có Node nào',
            'all_up_to_date' => 'Tất cả đã được cập nhật',
            'outdated' => ':outdated/:all lỗi thời',
        ],
        'panelversion' => [
            'label' => 'Phiên bản bảng điều khiển',
            'ok' => 'Bảng điều khiển đã được cập nhật',
            'failed' => 'Phiên bản đã cài đặt là :currentVersion nhưng phiên bản mới nhất là :latestVersion',
            'up_to_date' => 'Mới nhất',
            'outdated' => 'Lỗi thời',
        ],
        'schedule' => [
            'label' => 'Lịch trình',
            'ok' => 'Đồng ý',
            'failed_last_ran' => 'Lần chạy cuối cùng của lịch trình đã chạy cách đây hơn :time phút',
            'failed_not_ran' => 'Lịch trình vẫn chưa được chạy.',
        ],
        'useddiskspace' => [
            'label' => 'Dung lượng ổ đĩa',
        ],
    ],
    'checks' => [
        'successful' => 'Thành công',
        'failed' => 'Thất bại :checks',
    ],
];
