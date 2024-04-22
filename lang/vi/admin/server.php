<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'You are attempting to delete the default allocation for this server but there is no fallback allocation to use.',
        'marked_as_failed' => 'This server was marked as having failed a previous installation. Current status cannot be toggled in this state.',
        'bad_variable' => 'There was a validation error with the :name variable.',
        'daemon_exception' => 'There was an exception while attempting to communicate with the daemon resulting in a HTTP/:code response code. This exception has been logged. (request id: :request_id)',
        'default_allocation_not_found' => 'Không tìm thấy phân bổ mặc định được yêu cầu trong phân bổ của máy chủ này.',
    ],
    'alerts' => [
        'startup_changed' => 'Thiết lập cho trình khởi động của máy chủ này đã được cập nhật. Nếu egg của máy chủ này đã thay đổi thì việc tái cài đặt sẽ được bắt đầu ngay bây giờ.',
        'server_deleted' => 'Máy chủ đã được xóa thành công khỏi hệ thống.',
        'server_created' => 'Máy chủ đã được khởi tạo thành công trên bản điều khiển. Xin hãy cho daemon một ít phút để hoàn thiện việc cài đặt máy chủ này.',
        'build_updated' => 'Chi tiết bản dựng của máy chủ này đã được cập nhật. Một số thay đổi sẽ cần phải khởi động lại để có hiệu lực.',
        'suspension_toggled' => 'Trạng thái tạm dừng của máy chủ đã được thay đổi thành :status.',
        'rebuild_on_boot' => 'Máy chủ này đã được đánh dấu là cần phải xây dựng lại Docker Container. Điều này sẽ xảy ra ở lần khởi động máy chủ tiếp theo.',
        'install_toggled' => 'Tình trạng cài đặt của máy chủ này đã được thay đổi.',
        'server_reinstalled' => 'Máy chủ này đã được đưa vào hàng chờ cho việc tái cài đặt ngay bây giờ.',
        'details_updated' => 'Chi tiết về máy chủ đã được cập nhật thành công.',
        'docker_image_updated' => 'Đã thành công thay đổi gói Docker mặc định để sử dụng cho server này. Việc khởi động lại là cần thiết để áp dụng thay đổi này.',
        'node_required' => 'Bạn cần ít nhất một node đã được thiết lập trước khi bạn có thể thêm máy chủ vào bản điều khiển.',
        'transfer_nodes_required' => 'Bạn cần có ít nhất hai node đã được thiết lập trước khi bạn có thể chuyển dời máy chủ.',
        'transfer_started' => 'Việc chuyển dời máy chủ đã được bắt đầu.',
        'transfer_not_viable' => 'Node bạn đang chọn không có dung lượng ổ cứng hoặc bộ nhớ cần thiết để chứa máy chủ này.',
    ],
];
