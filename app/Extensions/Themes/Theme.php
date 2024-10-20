<?php

namespace App\Extensions\Themes;

class Theme
{
    public function js(string $path): string
    {
        return sprintf('<script src="%s"></script>' . PHP_EOL, $this->getUrl($path));
    }

    public function css(string $path): string
    {
        return sprintf('<link media="all" type="text/css" rel="stylesheet" href="%s"/>' . PHP_EOL, $this->getUrl($path));
    }

    protected function getUrl(string $path): string
    {
        return '/themes/panel/' . ltrim($path, '/');
    }
}
