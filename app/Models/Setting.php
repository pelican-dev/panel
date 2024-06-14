<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Setting extends Model
{
    use Sushi;

    protected $casts = [
        'limit' => 'integer',
        'options' => 'array',
    ];

    protected $fillable = ['key', 'label', 'value', 'type', 'options', 'description', 'limit', 'tabs'];

    protected $rows = [
        [
            'key' => 'FILAMENT_TOP_NAVIGATION',
            'label' => 'Topbar or Sidebar',
            'value' => 'false',
            'type' => 'text',
            'tabs' => 'Basic',
            'description' => 'Setting this to true switches the sidebar to a topbar and vice versa',
            //'options' => ['false' => 'False', 'true' => 'True'], TODO fix it so this is saved properly to storage/framework/cache/sushi-app-models-setting.sqlite
        ],
        [
            'key' => 'APP_NAME',
            'label' => 'Panel Name',
            'value' => 'Pelican',
            'tabs' => 'Basic',
            'type' => 'text',
            'description' => 'This is the name that is used throughout the panel and in emails sent to clients.',
            // 'limit' => 18, TODO fix it so this is saved properly to storage/framework/cache/sushi-app-models-setting.sqlite
        ],
        [
            'key' => 'MAIL_HOST',
            'label' => 'Mail Host',
            'value' => 'smtp.example.com',
            'type' => 'text',
            'tabs' => 'Mail',
            'description' => 'Enter the SMTP server address that mail should be sent through.',
        ],
    ];
}
