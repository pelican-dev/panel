<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;
use App\Traits\Commands\EnvironmentWriterTrait;

class Setting extends Model
{
    use EnvironmentWriterTrait;
    use Sushi;

    protected $casts = [
        'limit' => 'integer',
        'options' => 'array',
    ];

    protected $fillable = ['key', 'label', 'value', 'type', 'options', 'description', 'limit', 'tabs'];

    protected $DefaultSettings = [
        [
            'key' => 'FILAMENT_TOP_NAVIGATION',
            'label' => 'Topbar or Sidebar',
            'value' => 'false',
            'type' => 'toggle-buttons',
            'tabs' => 'Panel',
            'description' => 'Setting this to true switches the sidebar to a topbar and vice versa',
            'limit' => 255,
            //'options' => ['false' => 'False', 'true' => 'True'], TODO fix it so this is saved properly to storage/framework/cache/sushi-app-models-setting.sqlite
        ],
        [
            'key' => 'APP_NAME',
            'label' => 'Panel Name',
            'value' => 'Pelican',
            'tabs' => 'Panel',
            'type' => 'limit',
            'description' => 'This is the name that is used throughout the panel and in emails sent to clients.',
            'limit' => 18,
        ],
        [
            'key' => 'MAIL_HOST',
            'label' => 'SMTP Host',
            'value' => 'smtp.example.com',
            'type' => 'text',
            'tabs' => 'Mail',
            'description' => 'Enter the SMTP server address that mail should be sent through.',
            'limit' => 255,
        ],
        [
            'key' => 'MAIL_PORT',
            'label' => 'SMTP Port',
            'value' => '25',
            'type' => 'number',
            'tabs' => 'Mail',
            'description' => 'Enter the SMTP server port that mail should be sent through.',
            'limit' => 255,
        ],
        [
            'key' => 'MAIL_USERNAME',
            'label' => 'Username',
            'value' => '',
            'type' => 'text',
            'tabs' => 'Mail',
            'description' => 'The username to use when connecting to the SMTP server.',
            'limit' => 255,
        ],
        [
            'key' => 'MAIL_PASSWORD',
            'label' => 'Password',
            'value' => '',
            'type' => 'password',
            'tabs' => 'Mail',
            'limit' => 255,
            'description' => 'The password to use in conjunction with the SMTP username. Leave blank to continue using the existing password. To set the password to an empty value enter !e into the field.',
        ],
        [
            'key' => 'MAIL_ENCRYPTION',
            'label' => 'Encryption',
            'value' => 'tls',
            'type' => 'text', // TODO make this select as soon as it works
            'tabs' => 'Mail',
            'description' => 'Select the type of encryption to use when sending mail.',
            'limit' => 255,
        ],
        [
            'key' => 'MAIL_FROM_ADDRESS',
            'label' => 'Mail From',
            'value' => 'no-reply@example.com',
            'type' => 'text',
            'tabs' => 'Mail',
            'description' => 'Enter an email address that all outgoing emails will originate from.',
            'limit' => 255,
        ],
        [
            'key' => 'MAIL_FROM_NAME',
            'label' => 'Mail From Name',
            'value' => 'Pelican Admin',
            'type' => 'text',
            'tabs' => 'Mail',
            'description' => 'The name that emails should appear to come from.',
            'limit' => 255,
        ],
        [
            'key' => 'RECAPTCHA_ENABLED',
            'label' => 'Recaptcha Status',
            'value' => 'true',
            'type' => 'toggle-buttons',
            'tabs' => 'Advanced',
            'description' => 'If enabled, login forms and password reset forms will do a silent captcha check and display a visible captcha if needed.',
            'limit' => 255,
        ],
        [
            'key' => 'RECAPTCHA_WEBSITE_KEY',
            'label' => 'Recaptcha Site Key',
            'value' => '6LcJcjwUAAAAAO_Xqjrtj9wWufUpYRnK6BW8lnfn',
            'type' => 'password',
            'tabs' => 'Advanced',
            'description' => '',
            'limit' => 255,
        ],
        [
            'key' => 'RECAPTCHA_SECRET_KEY',
            'label' => 'Recaptcha Secret Key',
            'value' => '6LcJcjwUAAAAALOcDJqAEYKTDhwELCkzUkNDQ0J5',
            'type' => 'password',
            'tabs' => 'Advanced',
            'description' => 'Used for communication between your site and Google. Be sure to keep it a secret.',
            'limit' => 255,
        ],
        [
            'key' => 'PANEL_CLIENT_ALLOCATIONS_ENABLED',
            'label' => 'Automatic Allocation Status',
            'value' => 'false',
            'type' => 'toggle-buttons',
            'tabs' => 'Advanced',
            'description' => 'If enabled users will have the option to automatically create new allocations for their server via the frontend.',
            'limit' => 255,
        ],
        [
            'key' => 'PANEL_CLIENT_ALLOCATIONS_RANGE_START',
            'label' => 'Automatic Allocation Starting Port',
            'value' => '',
            'type' => 'number',
            'tabs' => 'Advanced',
            'description' => 'The starting port in the range that can be automatically allocated.',
            'limit' => 255,
        ],
        [
            'key' => 'PANEL_CLIENT_ALLOCATIONS_RANGE_END',
            'label' => 'Automatic Allocation Ending Port',
            'value' => '',
            'type' => 'number',
            'tabs' => 'Advanced',
            'description' => 'The ending port in the range that can be automatically allocated.',
            'limit' => 255,
        ],
    ];

    public function getRows()
    {
        $rows = $this->DefaultSettings;
        foreach ($rows as &$row) {
            $row['value'] = env($row['key']);
        }

        return $rows;
    }
}
