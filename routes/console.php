<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('serve', fn () => $this->error('You must use a separate webserver like Nginx, Caddy, or Apache.'));
