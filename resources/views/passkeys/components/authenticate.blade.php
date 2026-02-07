<div>
    @include('passkeys::components.partials.authenticateScript')

    <form id="passkey-login-form" method="POST" action="{{ route('passkeys.login') }}">
        @csrf
    </form>

    @if($message = session()->get('authenticatePasskey::message'))
        @php
            \Filament\Notifications\Notification::make()
                ->title($message)
                ->danger()
                ->send();
            session()->forget('authenticatePasskey::message');
        @endphp
    @endif
</div>
