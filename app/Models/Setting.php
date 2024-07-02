<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;
use App\Traits\Commands\EnvironmentWriterTrait;

/**
 * @property string $key
 * @property string $config
 * @property string $label
 * @property string $value
 * @property string $type
 * @property string $tabs
 * @property string $description
 * @property int $limit
 */
class Setting extends Model
{
    use EnvironmentWriterTrait;
    use Sushi;

    protected $casts = [
        'limit' => 'integer',
    ];

    protected $fillable = ['key', 'label', 'value', 'type', 'description', 'limit', 'tabs'];

    public const DEFAULT = [
        [
            'key' => 'FILAMENT_TOP_NAVIGATION',
            'config' => 'panel.filament.top-navigation',
            'label' => 'Topbar or Sidebar',
            'value' => 'false',
            'type' => 'toggle-buttons',
            'tabs' => 'Panel',
            'description' => 'Setting this to true switches the sidebar to a topbar and vice versa',
            'limit' => 255,
        ],
        [
            'key' => 'APP_NAME',
            'config' => 'app.name',
            'label' => 'Panel Name',
            'value' => 'Pelican',
            'tabs' => 'Panel',
            'type' => 'limit',
            'description' => 'This is the name that is used throughout the panel and in emails sent to clients.',
            'limit' => 18,
        ],
        [
            'key' => 'MAIL_HOST',
            'config' => 'mail.mailers.smtp.host',
            'label' => 'SMTP Host',
            'value' => 'smtp.example.com',
            'type' => 'text',
            'tabs' => 'Mail',
            'description' => 'Enter the SMTP server address that mail should be sent through.',
            'limit' => 255,
        ],
        [
            'key' => 'MAIL_PORT',
            'config' => 'mail.mailers.smtp.port',
            'label' => 'SMTP Port',
            'value' => '25',
            'type' => 'number',
            'tabs' => 'Mail',
            'description' => 'Enter the SMTP server port that mail should be sent through.',
            'limit' => 255,
        ],
        [
            'key' => 'MAIL_USERNAME',
            'config' => 'mail.mailers.smtp.username',
            'label' => 'Username',
            'value' => '',
            'type' => 'text',
            'tabs' => 'Mail',
            'description' => 'The username to use when connecting to the SMTP server.',
            'limit' => 255,
        ],
        [
            'key' => 'MAIL_PASSWORD',
            'config' => 'mail.mailers.smtp.password',
            'label' => 'Password',
            'value' => '',
            'type' => 'password',
            'tabs' => 'Mail',
            'limit' => 255,
            'description' => 'The password to use in conjunction with the SMTP username. Leave blank to continue using the existing password. To set the password to an empty value enter !e into the field.',
        ],
        [
            'key' => 'MAIL_ENCRYPTION',
            'config' => 'mail.mailers.smtp.encryption',
            'label' => 'Encryption',
            'value' => 'tls',
            'type' => 'text',
            'tabs' => 'Mail',
            'description' => 'Select the type of encryption to use when sending mail.',
            'limit' => 255,
        ],
        [
            'key' => 'MAIL_FROM_ADDRESS',
            'config' => 'mail.from.address',
            'label' => 'Mail From',
            'value' => 'no-reply@example.com',
            'type' => 'text',
            'tabs' => 'Mail',
            'description' => 'Enter an email address that all outgoing emails will originate from.',
            'limit' => 255,
        ],
        [
            'key' => 'MAIL_FROM_NAME',
            'config' => 'mail.from.name',
            'label' => 'Mail From Name',
            'value' => 'Pelican Admin',
            'type' => 'text',
            'tabs' => 'Mail',
            'description' => 'The name that emails should appear to come from.',
            'limit' => 255,
        ],
    ];

    public function getRows()
    {
        $rows = self::DEFAULT;
        foreach ($rows as &$row) {
            $row['value'] = env($row['key']);
        }

        return $rows;
    }
}
