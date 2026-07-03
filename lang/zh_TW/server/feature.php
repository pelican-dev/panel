<?php

return [
    'restart_now' => '伺服器現在將會重新啟動。',
    'close' => '關閉',

    'eula' => [
        'heading' => 'Minecraft EULA',
        'description' => '按下方的「我同意」，即表示您同意 <x-filament::link href="https://minecraft.net/eula" target="_blank">Minecraft EULA </x-filament::link>。',
        'accept' => '我同意',
        'accepted' => '已同意 Minecraft EULA',
        'failed' => '無法同意 Minecraft EULA',
    ],

    'gsl_token' => [
        'heading' => '無效的 GSL Token',
        'description' => '您的遊戲伺服器登入權杖（GSL Token）似乎無效或已過期。',
        'submit' => '更新 GSL Token',
        'info' => '您可以 <x-filament::link href="https://steamcommunity.com/dev/managegameservers" target="_blank">產生一個新的權杖</x-filament::link> 並在下方輸入，或者將欄位留空以將其完全移除。',
        'updated' => '已更新 GSL Token',
        'failed' => '無法更新 GSL Token',
    ],

    'java_version' => [
        'heading' => '不支援的 Java 版本',
        'description' => '此伺服器目前執行不支援的 Java 版本，因此無法啟動。',
        'submit' => '更新 Docker 映像檔',
        'select_version' => '請從下方清單選取受支援的版本，以繼續啟動伺服器。',
        'docker_image' => 'Docker 映像檔',
        'updated' => 'Docker 映像檔已更新',
        'failed' => '無法更新 Docker 映像檔',
    ],

    'pid_limit' => [
        'heading_admin' => '已達記憶體或程序限制...',
        'heading_user' => '可能已達資源限制...',
        'description_admin' => '<p>此伺服器已達到最大程序或記憶體限制。</p><p class="mt-4">在 Wings 設定檔 <code>config.yml</code> 中增加 <code>container_pid_limit</code> 可能有助於解決此問題。</p><p class="mt-4"><b>注意：必須重新啟動 Wings，設定檔變更才會生效</b></p>',
        'description_user' => '<p>此伺服器正嘗試使用比分配的還要多的資源。請聯絡管理員並向他們提供下方的錯誤訊息。</p><p class="mt-4"><code>pthread_create failed, Possibly out of memory or process/resource limits reached</code></p>',
    ],

    'steam_disk_space' => [
        'heading' => '可用磁碟空間不足...',
        'description_admin' => '<p>此伺服器已耗盡可用的磁碟空間，無法完成安裝或更新程序。</p><p class="mt-4">請在代管此伺服器的機器上輸入 <code class="rounded py-1 px-2">df -h</code>，確保該機器有足夠的磁碟空間。刪除檔案或增加可用磁碟空間以解決此問題。</p>',
        'description_user' => '<p>此伺服器已耗盡可用的磁碟空間，無法完成安裝或更新程序。請與管理員聯絡，並告知他們磁碟空間的問題。</p>',
    ],
];
