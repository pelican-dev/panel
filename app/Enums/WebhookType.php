<?php

namespace App\Enums;

enum WebhookType: string
{
    case Standalone = 'standalone';
    case Discord = 'discord';

    public function icon(): string
    {
        return match ($this) {
            self::Standalone => 'tabler-world-www',
            self::Discord => 'tabler-brand-discord',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Standalone => null,
            self::Discord => '#5865F2',
        };
    }
}
