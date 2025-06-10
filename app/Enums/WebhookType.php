<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Colors\Color;

enum WebhookType: string implements HasColor, HasIcon, HasLabel
{
    case Regular = 'regular';
    case Discord = 'discord';

    public function getLabel(): string
    {
        return match ($this) {
            self::Regular => trans('admin/webhook.regular'),
            self::Discord => 'Discord',
        };
    }

    /**
     * @return array<int, mixed>|null
     */
    public function getColor(): ?array
    {
        return match ($this) {
            self::Regular => null,
            self::Discord => Color::hex('#5865F2'),
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Regular => 'tabler-world-www',
            self::Discord => 'tabler-brand-discord',
        };
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public static function columnoptions(): array
    {
        return [
            self::Regular->value => [
                'icon' => self::Regular->getIcon(),
                'color' => null,
            ],
            self::Discord->value => [
                'icon' => self::Discord->getIcon(),
                'color' => '#5865F2',
            ],
        ];
    }
}
