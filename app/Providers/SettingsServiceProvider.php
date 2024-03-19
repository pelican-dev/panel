<?php

namespace App\Providers;

use App\Models\Setting;
use Exception;
use Psr\Log\LoggerInterface as Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * An array of configuration keys to override with database values
     * if they exist.
     */
    protected array $keys = [
        'app:name',
        'app:locale',
        'recaptcha:enabled',
        'recaptcha:secret_key',
        'recaptcha:website_key',
        'panel:guzzle:timeout',
        'panel:guzzle:connect_timeout',
        'panel:console:count',
        'panel:console:frequency',
        'panel:auth:2fa_required',
        'panel:client_features:allocations:enabled',
        'panel:client_features:allocations:range_start',
        'panel:client_features:allocations:range_end',
    ];

    /**
     * Keys specific to the mail driver that are only grabbed from the database
     * when using the SMTP driver.
     */
    protected array $emailKeys = [
        'mail:mailers:smtp:host',
        'mail:mailers:smtp:port',
        'mail:mailers:smtp:encryption',
        'mail:mailers:smtp:username',
        'mail:mailers:smtp:password',
        'mail:from:address',
        'mail:from:name',
    ];

    /**
     * Keys that are encrypted and should be decrypted when set in the
     * configuration array.
     */
    protected static array $encrypted = [
        'mail:mailers:smtp:password',
    ];

    /**
     * Boot the service provider.
     */
    public function boot(Log $log): void
    {
        // Only set the email driver settings from the database if we
        // are configured using SMTP as the driver.
        if (config('mail.default') === 'smtp') {
            $this->keys = array_merge($this->keys, $this->emailKeys);
        }

        try {
            $values = Setting::all()->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->value];
            })->toArray();
        } catch (QueryException $exception) {
            $log->notice('A query exception was encountered while trying to load settings from the database: ' . $exception->getMessage());

            return;
        }

        foreach ($this->keys as $key) {
            $value = array_get($values, 'settings::' . $key, config(str_replace(':', '.', $key)));
            if (in_array($key, self::$encrypted)) {
                try {
                    $value = decrypt($value);
                } catch (Exception) {
                    // ignore
                }
            }

            switch (strtolower($value)) {
                case 'true':
                case '(true)':
                    $value = true;
                    break;
                case 'false':
                case '(false)':
                    $value = false;
                    break;
                case 'empty':
                case '(empty)':
                    $value = '';
                    break;
                case 'null':
                case '(null)':
                    $value = null;
            }

            config()->set(str_replace(':', '.', $key), $value);
        }
    }

    public static function getEncryptedKeys(): array
    {
        return self::$encrypted;
    }
}
