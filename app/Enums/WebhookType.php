<?php

namespace App\Enums;

enum WebhookType: string
{
    case Regular = 'standalone';
    case Discord = 'discord';

    public function icon(): string
    {
        return match ($this) {
            self::Regular => 'tabler-world-www',
            self::Discord => 'tabler-brand-discord',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Regular => null,
            self::Discord => '#5865F2',
        };
    }
}
