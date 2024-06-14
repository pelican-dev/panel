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
            'key' => 'APP_NAME',
            'label' => 'Panel Name',
            'value' => 'Pelican',
            'group' => 'Basic',
            'type' => 'text',
            //'limit' => 18,
            'description' => 'This is the name that is used throughout the panel and in emails sent to clients.',
        ],
    ];
}
