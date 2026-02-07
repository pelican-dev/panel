@include('passkeys.components.authenticate')

<x-filament::button icon="heroicon-o-key" color="gray" class="w-full" onclick="authenticateWithPasskey()">
    {{ __('passkeys.authenticate_using_passkey') }}
</x-filament::button>
