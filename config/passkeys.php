<?php

use App\Helpers\PasskeyOrigin;
use App\Http\Middleware\AddPasskeyErrorMessage;
use App\Http\Middleware\AllowPasskeyOrigin;

$appOrigin = PasskeyOrigin::normalize(config('app.url')) ?? 'http://localhost';

return [
    /*
     * Passkeys are bound to this domain. It has to match the host users actually browse
     * the panel at, otherwise the browser refuses to run the ceremony at all.
     */
    'relying_party_id' => env('PASSKEYS_RELYING_PARTY_ID') ?: parse_url($appOrigin, PHP_URL_HOST),

    /*
     * Only ceremonies completed on one of these origins are accepted. Any origin on the
     * relying party domain itself is added per request by AllowPasskeyOrigin, so this only
     * needs entries for hosts that differ from it - subdomains, when the relying party ID
     * is a parent domain shared by several panels. List those in PASSKEYS_ALLOWED_ORIGINS
     * as a comma separated list; browsers reject anything outside the relying party domain
     * before it ever reaches us.
     */
    'allowed_origins' => PasskeyOrigin::all(array_merge(
        [$appOrigin],
        explode(',', (string) env('PASSKEYS_ALLOWED_ORIGINS')),
    )),

    'middleware' => ['web', AddPasskeyErrorMessage::class, AllowPasskeyOrigin::class],
    'management_middleware' => ['auth'],
];
