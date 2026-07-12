<?php

namespace App\Enums;

enum ServerUserSettingKey: string
{
    case BackupNotifications = 'backup_notifications';

    /**
     * The default value for users without an explicit setting. Server owners
     * may receive different defaults, see User::getServerSetting().
     */
    public function getDefaultValue(): bool
    {
        return match ($this) {
            self::BackupNotifications => false,
        };
    }

    /** @return array<string, bool> */
    public static function getDefaultSettings(): array
    {
        $default = [];

        foreach (self::cases() as $key) {
            $default[$key->value] = $key->getDefaultValue();
        }

        return $default;
    }
}
