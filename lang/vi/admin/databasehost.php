<?php

return [
    'nav_title' => 'Máy chủ cơ sở dữ liệu',
    'model_label' => 'Máy chủ cơ sở dữ liệu',
    'model_label_plural' => 'Máy chủ cơ sở dữ liệu',
    'table' => [
        'database' => 'Cơ sở dữ liệu',
        'name' => 'Tên',
        'host' => 'Máy chủ',
        'port' => 'Cổng',
        'name_helper' => 'Để trống mục này sẽ tự động tạo ra một tên ngẫu nhiên',
        'username' => 'Tên người dùng',
        'password' => 'Mật khẩu',
        'remote' => 'Kết nối từ',
        'remote_helper' => 'Nơi cho phép kết nối. Để trống nếu muốn cho phép kết nối từ bất kỳ đâu.',
        'max_connections' => 'Kết nối tối đa',
        'created_at' => 'Tạo lúc',
        'connection_string' => 'Chuỗi kết nối JDBC',
    ],
    'error' => 'Có lỗi khi kết nối đến máy chủ',
    'host' => 'Máy chủ',
    'host_help' => 'Địa chỉ IP hoặc tên miền được sử dụng để kết nối đến máy chủ MySQL từ Panel để tạo cơ sở dữ liệu mới.',
    'port' => 'Cổng',
    'port_help' => 'Cổng dịch vụ MySQL đang chạy trên máy này.',
    'max_database' => 'Cơ sở dữ liệu tối đa',
    'max_databases_help' => 'Số cơ sở dữ liệu được tạo tối đa trên máy chủ này. Nếu đạt giới hạn, không thể tạo thêm cơ sở dữ liệu mới. Bỏ trống là không giới hạn.',
    'display_name' => 'Tên hiển thị',
    'display_name_help' => 'Địa chỉ IP hoặc tên miền được cung cấp cho người dùng.',
    'username' => 'Tên người dùng',
    'username_help' => 'Tên người dùng của tài khoản có quyền tạo thêm người dùng và cơ sở dữ liệu trên hệ thống.',
    'password' => 'Mật khẩu',
    'password_help' => 'Mật khẩu cho người dùng cơ sở dữ liệu.',
    'linked_nodes' => 'Node đã kết nối',
    'linked_nodes_help' => 'Cài đặt này chỉ mặc định cho cơ sở dữ liệu khi thêm vào Node đó.',
    'connection_error' => 'Lỗi khi kết nối đến cơ sở dữ liệu',
    'no_database_hosts' => 'Không có cơ sở dữ liệu nào',
    'no_nodes' => 'Không có Node nào',
    'delete_help' => 'Máy chủ cơ sở dữ liệu có chứa cơ sở dữ liệu',
    'unlimited' => 'Không giới hạn',
    'anywhere' => 'Bất cứ đâu',

    'rotate' => 'Đảo',
    'rotate_password' => 'Đảo mật khẩu',
    'rotated' => 'Mật khẩu đã được đảo',
    'rotate_error' => 'Không thể đảo mật khẩu',
    'databases' => 'Cơ sở dữ liệu',

    'setup' => [
        'preparations' => 'Chuẩn bị',
        'database_setup' => 'Cài đặt cơ sở dữ liệu',
        'panel_setup' => 'Cài đặt bảng điều khiển',

        'note' => 'Hiện tại chỉ hỗ trợ cơ sở dữ liệu MySQL/MariaDB!',
        'different_server' => 'Panel và cơ sở dữ liệu có cùng máy chủ <i>không</i>?',

        'database_user' => 'Người dùng cơ sở dữ liệu',
        'cli_login' => 'Hãy nhập lệnh <code>mysql -u root -p</code> để truy cập dòng lệnh cơ sở dữ liệu.',
        'command_create_user' => 'Lệnh để tạo người dùng',
        'command_assign_permissions' => 'Lệnh để gán quyền',
        'cli_exit' => 'Để thoát mysql cli hãy chạy <code>exit</code>.',
        'external_access' => 'Truy cập từ bên ngoài',
        'allow_external_access' => '
<p>Rất có thể bạn cần cho phép truy cập từ bên ngoài vào phiên bản MySQL này để cho phép các máy chủ kết nối với nó.</p>

<br>

<p>Để làm điều này, hãy mở tệp <code>my.cnf</code>, vị trí của tệp này có thể khác nhau tùy thuộc vào hệ điều hành và cách cài đặt MySQL của bạn. Bạn có thể gõ lệnh find <code>/etc -iname my.cnf</code> để tìm vị trí của nó.</p>

<br>

<p>Mở tệp <code>my.cnf</code>, thêm đoạn văn bản bên dưới vào cuối tệp và lưu lại:<br>

<code>[mysqld]<br>bind-address=0.0.0.0</code></p>

<br>

<p>Khởi động lại MySQL/MariaDB để áp dụng các thay đổi này. Thao tác này sẽ ghi đè lên cấu hình MySQL mặc định, theo mặc định chỉ chấp nhận các yêu cầu từ localhost. Việc cập nhật này sẽ cho phép kết nối trên tất cả các giao diện, và do đó, cho phép kết nối từ bên ngoài. Hãy đảm bảo cho phép cổng MySQL (mặc định là 3306) trong tường lửa của bạn.</p>                                ',
    ],
];
