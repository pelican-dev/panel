<x-filament::widget>
    @assets
    @php
        $userFont = auth()->user()->getCustomization()['console_font'] ?? 'monospace';
        $userFontSize = auth()->user()->getCustomization()['console_font_size'] ?? 14;
        $userRows =  auth()->user()->getCustomization()['console_rows'] ?? 30;
        $socketUrl = $this->getSocket();  // Assuming this is the WebSocket URL
    @endphp

    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <script>
        window.phpData = {
            userFont: '{{ $userFont }}',
            userFontSize: {{ $userFontSize }},
            userRows: {{ $userRows }},
            socketUrl: '{{ $socketUrl }}',
        };
    </script>
    @endassets

    <div id="terminal" wire:ignore></div>

    @if ($this->authorizeSendCommand())
        <div class="flex items-center w-full border-top overflow-hidden dark:bg-gray-900"
             style="border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;">
            <x-filament::icon
                icon="tabler-chevrons-right"
            />
            <input
                id="send-command"
                class="w-full focus:outline-none focus:ring-0 border-none dark:bg-gray-900"
                type="text"
                :readonly="{{ $this->canSendCommand() ? 'false' : 'true' }}"
                title="{{ $this->canSendCommand() ? '' : 'Can\'t send command when the server is Offline' }}"
                placeholder="{{ $this->canSendCommand() ? 'Type a command...' : 'Server Offline...' }}"
                wire:model="input"
                wire:keydown.enter="enter"
                wire:keydown.up.prevent="up"
                wire:keydown.down="down"
            >
        </div>
    @endif
</x-filament::widget>
