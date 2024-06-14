<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Setting extends Model
{
    use Sushi;

    protected $casts = [
        'attributes' => 'array',
        'limit' => 'integer',
    ];

    protected $fillable = ['key', 'label', 'value', 'type', 'attributes', 'description', 'limit', 'tabs'];

    protected $rows = [
        [
            'key' => 'FILAMENT_TOP_NAVIGATION',
            'label' => 'Topbar or Sidebar',
            'group' => 'Panel',
            'value' => 'false',
            'type' => 'text',
            'description' => 'Setting this to true switches the sidebar to a topbar and vice versa',
        ],
        [
            'key' => 'FILAMENT_TOP_NAVIGATION',
            'label' => 'Topbar or Sidebar',
            'group' => 'Panel',
            'value' => 'false',
            'type' => 'text',
            'description' => 'Setting this to true switches the sidebar to a topbar and vice versa',
        ],
        [
            'key' => 'MAIL_HOST',
            'label' => 'Mail Host',
            'value' => 'smtp.example.com',
            'group' => 'Basic',
            'type' => 'text',
            //'limit' => 18,
            'description' => 'Enter the SMTP server address that mail should be sent through.',
        ],
    ];
}
