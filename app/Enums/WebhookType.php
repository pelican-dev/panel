<?php

namespace App\Enums;

enum WebhookType: string
{
    case Standalone = 'standalone';
    case Discord = 'discord';
}
