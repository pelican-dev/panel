<?php

namespace App\Enums;

enum WebhookScope: string
{
    case GLOBAL = 'global';
    case SERVER = 'server';

    public function getLabel(): string
    {
        return match ($this) {
            self::GLOBAL => 'Global',
            self::SERVER => 'Server',
        };
    }
}
