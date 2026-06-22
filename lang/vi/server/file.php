<?php

return [
    'title' => 'Tệp',
    'name' => 'Tên',
    'size' => 'Kích cỡ',
    'modified_at' => 'Sửa lúc',
    'actions' => [
        'open' => 'Mở',
        'download' => 'Tải xuống',
        'copy' => [
            'title' => 'Sao chép',
            'notification' => 'Tệp đã sao chép',
        ],
        'upload' => [
            'title' => 'Tải lên',
            'from_files' => 'Tải lên tệp',
            'from_url' => 'Tải từ liên kết',
            'url' => 'Liên kết',
            'drop_files' => 'Kéo thả để tải lên',
            'success' => 'Tệp đã tải lên thành công',
            'failed' => 'Tải lên tệp thất bại',
            'header' => 'Tải lên tệp',
            'error' => 'Xảy ra lỗi khi đang tải lên',
        ],
        'rename' => [
            'title' => 'Đổi tên',
            'file_name' => 'Tên tệp tin',
            'notification' => 'Đã đổi tên tệp',
        ],
        'move' => [
            'title' => 'Di chuyển',
            'directory' => 'Thư mục',
            'directory_hint' => 'Nhập đường dẫn mới tương đối đến vị trí này.',
            'new_location' => 'Vị trí mới',
            'new_location_hint' => 'Nhập vị trí của tệp mới tương đối với tệp này',
            'notification' => 'Tệp đã được di chuyển',
            'bulk_notification' => ':count tệp đã được chuyển đến :directory',
        ],
        'permissions' => [
            'title' => 'Quyền',
            'read' => 'Đọc',
            'write' => 'Ghi',
            'execute' => 'Chạy',
            'owner' => 'Chủ',
            'group' => 'Nhóm',
            'public' => 'Công cộng',
            'notification' => 'Quyền được chuyển thành :mode',
        ],
        'archive' => [
            'title' => 'Nén',
            'archive_name' => 'Tên tệp nén',
            'notification' => 'Đã tạo tệp nén',
            'extension' => 'Đuôi',
        ],
        'unarchive' => [
            'title' => 'Giải nén',
            'notification' => 'Giải nén thành công',
        ],
        'new_file' => [
            'title' => 'Tệp mới',
            'file_name' => 'Tên tệp mới',
            'syntax' => 'Tô màu mã',
            'create' => 'Tạo',
        ],
        'new_folder' => [
            'title' => 'Thư mục mới',
            'folder_name' => 'Tên thư mục mới',
        ],
        'nested_search' => [
            'title' => 'Tìm kiếm sâu',
            'search_term' => 'Cụm từ tìm kiếm',
            'search_term_placeholder' => 'Nhập cụm từ tìm kiếm, kiểu như *.txt',
            'search' => 'Tìm kiếm',
            'search_for_term' => 'Tìm :term',
        ],
        'delete' => [
            'notification' => 'Tệp đã bị xóa',
            'bulk_notification' => ':count tệp đã bị xoá',
        ],
        'edit' => [
            'title' => 'Đang sửa: :file',
            'save_close' => 'Lưu và đóng',
            'save' => 'Lưu',
            'cancel' => 'Hủy',
            'notification' => 'Tệp đã lưu',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> quá to!',
            'body' => 'Giới hạn là :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> không tìm thấy',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> là thư mục',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> đã tồn tại!',
        ],
        'files_node_error' => [
            'title' => 'Không thể tải tệp tin.',
        ],
        'pelicanignore' => [
            'title' => 'Bạn đang sửa tệp <code>.pelicanignore</code>',
            'body' => 'Tất cả các tệp trong này đều được loại khỏi tệp sao lưu. Hỗ trợ wildcard (<code>*</code>).<br>Loại trừ mục băng dấu chấm than (<code>!</code>).',
        ],
    ],
];
