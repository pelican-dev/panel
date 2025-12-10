<?php

namespace App\Enums;

enum CustomRenderHooks: string
{
    case FooterStart = 'pelican::footer.start';
    case FooterEnd = 'pelican::footer.end';
}
