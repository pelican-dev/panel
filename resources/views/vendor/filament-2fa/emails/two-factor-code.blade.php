<x-mail::message>
{{ __('Hello') }},

{{ __('You recently requested to log in to your account. To complete the login, please use the following two-factor authentication (2FA) code:') }}

<div style="background-color: #f0f0f0; padding: 10px; border-radius: 5px; text-align: center; max-width: 200px; font-family: monospace; margin: 25px auto 25px;">
    <span style="font-size: 1.5em; color: #333;">
        {{ $code }}
    </span>
</div>


{{ __('If you didn\'t try to log in, please change your password immediately to protect your account.') }}

{{ __('Kind regards') }},<br>
{{ config('app.name') }}
</x-mail::message>