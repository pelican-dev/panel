<?php

return [
    'restart_now' => '伺服器將立即重新啟動。',
    'close' => '關閉',

    'eula' => [
        'heading' => 'Minecraft EULA',
        'description' => '按下下方的「我同意」即表示您同意 <x-filament::link href="https://minecraft.net/eula" target="_blank">Minecraft EULA </x-filament::link>。',
        'accept' => '我同意',
        'accepted' => '已同意 Minecraft EULA',
        'failed' => '無法同意 Minecraft EULA',
    ],

    'gsl_token' => [
        'heading' => '無效的 GSL Token',
        'description' => '您的遊戲伺服器登入權杖 (GSL Token) 似乎無效或已過期。',
        'submit' => '更新 GSL Token',
        'info' => '您可以<x-filament::link href="https://steamcommunity.com/dev/managegameservers" target="_blank">產生一個新的</x-filament::link>並在下方輸入，或將此欄位留空以將其完全移除。',
        'updated' => 'GSL Token 已更新',
        'failed' => '無法更新 GSL Token',
    ],

    'java_version' => [
        'heading' => '不支援的 Java 版本',
        'description' => '此伺服器目前執行不支援的 Java 版本，無法啟動。',
        'submit' => '更新 Docker 映像檔',
        'select_version' => '請從下方清單選擇支援的版本以繼續啟動伺服器。',
        'docker_image' => 'Docker 映像檔',
        'updated' => 'Docker 映像檔已更新',
        'failed' => '無法更新 Docker 映像檔',
    ],

    'pid_limit' => [
        'heading_admin' => '已達到記憶體或處理程序限制...',
        'heading_user' => '可能已達到資源限制...',
        'description_admin' => '<p>此伺服器已達到最大的處理程序或記憶體限制。</p><p class="mt-4">增加 wings 設定檔 <code>config.yml</code> 中的 <code>container_pid_limit</code> 可能有助於解決此問題。</p><p class="mt-4"><b>注意：必須重新啟動 Wings 才能使設定檔變更生效</b></p>',
        'description_user' => '<p>此伺服器嘗試使用超過分配的資源。請聯絡管理員並提供下方錯誤訊息。</p><p class="mt-4"><code>pthread_create failed, Possibly out of memory or process/resource limits reached</code></p>',
    ],

    'steam_disk_space' => [
        'heading' => '可用磁碟空間不足...',
        'description_admin' => '<p>此伺服器已耗盡可用磁碟空間，無法完成安裝或更新程序。</p><p class="mt-4">請在代管此伺服器的機器上輸入 <code class="rounded py-1 px-2">df -h</code> 以確保機器有足夠的磁碟空間。刪除檔案或增加可用磁碟空間以解決此問題。</p>',
        'description_user' => '<p>此伺服器已耗盡可用磁碟空間，無法完成安裝或更新程序。請與管理員聯絡並告知磁碟空間問題。</p>',
    ],
];
