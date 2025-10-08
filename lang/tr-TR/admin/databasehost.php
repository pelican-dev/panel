<?php

return [
    'nav_title' => 'Veritabanı Sunucuları',
    'model_label' => 'Veritabanı Sunucusu',
    'model_label_plural' => 'Veritabanı Sunucuları',
    'table' => [
        'database' => 'Veritabanı',
        'name' => 'Ad',
        'host' => 'Sunucu',
        'port' => 'Port',
        'name_helper' => 'Boş bırakılırsa rastgele bir ad otomatik olarak oluşturulur.',
        'username' => 'Kullanıcı Adı',
        'password' => 'Şifre',
        'remote' => 'Bağlantı Kaynağı',
        'remote_helper' => 'Bağlantıların nereden izin verileceği. Her yerden bağlantıya izin vermek için boş bırakın.',
        'max_connections' => 'Maksimum Bağlantı',
        'created_at' => 'Oluşturulma Tarihi',
        'connection_string' => 'JDBC Bağlantı Dizesi',
    ],
    'error' => 'Sunucuya bağlanırken hata oluştu',
    'host' => 'Sunucu',
    'host_help' => 'Bu Panelden yeni veritabanları oluşturmak için bu MySQL sunucusuna bağlanırken kullanılacak IP adresi veya alan adı.',
    'port' => 'Port',
    'port_help' => 'Bu sunucu için MySQL\'in çalıştığı port.',
    'max_database' => 'Maksimum Veritabanı',
    'max_databases_help' => 'Bu sunucu üzerinde oluşturulabilecek maksimum veritabanı sayısı. Limit dolduğunda, bu sunucu üzerinde yeni veritabanı oluşturulamaz. Boş bırakılırsa sınırsızdır.',
    'display_name' => 'Görünen Ad',
    'display_name_help' => 'Bu konumu diğerlerinden ayırt etmek için kullanılan kısa bir tanımlayıcı. 1 ile 60 karakter arasında olmalıdır, örneğin, us.nyc.lvl3.',
    'username' => 'Kullanıcı Adı',
    'username_help' => 'Sistem üzerinde yeni kullanıcılar ve veritabanları oluşturmak için yeterli izinlere sahip bir hesabın kullanıcı adı.',
    'password' => 'Şifre',
    'password_help' => 'Veritabanı kullanıcısının şifresi.',
    'linked_nodes' => 'Bağlı Düğümler',
    'linked_nodes_help' => 'Bu ayar, yalnızca seçilen Düğüm üzerindeki bir sunucuya veritabanı eklerken bu veritabanı sunucusunu varsayılan olarak kullanır.',
    'connection_error' => 'Veritabanı sunucusuna bağlanırken hata oluştu',
    'no_database_hosts' => 'Veritabanı Sunucusu Yok',
    'no_nodes' => 'Düğüm Yok',
    'delete_help' => 'Veritabanı Sunucusunda Veritabanları Var',
    'unlimited' => 'Sınırsız',
    'anywhere' => 'Her Yerden',

    'rotate' => 'Döndür',
    'rotate_password' => 'Şifreyi Döndür',
    'rotated' => 'Şifre Döndürüldü',
    'rotate_error' => 'Şifre Döndürme Başarısız',
    'databases' => 'Veritabanları',

    'setup' => [
        'preparations' => 'Hazırlıklar',
        'database_setup' => 'Veritabanı Kurulumu',
        'panel_setup' => 'Panel Kurulumu',

        'note' => 'Şu anda yalnızca MySQL/MariaDB veritabanları veritabanı sunucuları için desteklenmektedir!',
        'different_server' => 'Panel ve veritabanı aynı sunucuda <i>değil</i> mi?',

        'database_user' => 'Veritabanı Kullanıcısı',
        'cli_login' => 'MySQL CLI\'ye erişmek için <code>mysql -u root -p</code> komutunu kullanın.',
        'command_create_user' => 'Kullanıcı oluşturma komutu',
        'command_assign_permissions' => 'Yetki atama komutu',
        'cli_exit' => 'MySQL CLI\'den çıkmak için <code>exit</code> komutunu çalıştırın.',
        'external_access' => 'Harici Erişim',
        'allow_external_access' => '<p>Sunucuların bu MySQL örneğine bağlanabilmesi için büyük ihtimalle harici erişime izin vermeniz gerekecek.</p>
<br>
<p>Bunu yapmak için, işletim sisteminize ve MySQL’in nasıl kurulduğuna bağlı olarak konumu değişen <code>my.cnf</code> dosyasını açın. Dosyayı bulmak için <code>find /etc -iname my.cnf</code> komutunu çalıştırabilirsiniz.</p>
<br>
<p><code>my.cnf</code> dosyasını açın, aşağıdaki metni dosyanın en altına ekleyip kaydedin:<br>
<code>[mysqld]<br>bind-address=0.0.0.0</code></p>
<br>
<p>Değişikliklerin uygulanması için MySQL/MariaDB’yi yeniden başlatın. Bu işlem, varsayılan olarak yalnızca localhost’tan gelen istekleri kabul eden MySQL yapılandırmasını geçersiz kılar. Güncellemeden sonra tüm arayüzlerden, dolayısıyla harici bağlantılardan erişim sağlanabilir. Ayrıca güvenlik duvarınızda MySQL portuna (varsayılan 3306) izin verdiğinizden emin olun.</p>',
    ],
];
