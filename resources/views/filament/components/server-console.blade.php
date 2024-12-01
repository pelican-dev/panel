<x-filament::widget>
    @assets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xterm/5.5.0/xterm.js" integrity="sha512-Gujw5GajF5is3nMoGv9X+tCMqePLL/60qvAv1LofUZTV9jK8ENbM9L+maGmOsNzuZaiuyc/fpph1KT9uR5w3CQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/xterm/5.5.0/xterm.css" integrity="sha512-AbNrj/oSHJaILgcdnkYm+DQ08SqVbZ8jlkJbFyyS1WDcAaXAcAfxJnCH69el7oVgTwVwyA5u5T+RdFyUykrV3Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endassets

    <div id="terminal" wire:ignore></div>

    <div>
        <input
            class="w-full bg-transparent"
            type="text"
            placeholder="Type a command..."
            wire:model="input"
            wire:keydown.enter="enter"
            wire:keydown.up.prevent="up"
            wire:keydown.down="down"
        >
    </div>

    @script
    <script>
        let options = {
            fontSize: 18,
            // fontFamily: th('fontFamily.mono'),
            disableStdin: true,
            cursorStyle: 'underline',
            allowTransparency: true,
            rows: 35,
            cols: 110,
            // theme: theme,
        };

        const terminal = new Terminal(options);
        // TODO: load addons
        terminal.open(document.getElementById('terminal'));

        const TERMINAL_PRELUDE = '\u001b[1m\u001b[33mpelican@' + '{{ \Filament\Facades\Filament::getTenant()->name }}' + ' ~ \u001b[0m';

        const handleConsoleOutput = (line, prelude = false) =>
            terminal.writeln((prelude ? TERMINAL_PRELUDE : '') + line.replace(/(?:\r\n|\r|\n)$/im, '') + '\u001b[0m');

        const handleTransferStatus = (status) => {
            switch (status) {
                // Sent by either the source or target node if a failure occurs.
                case 'failure':
                    terminal.writeln(TERMINAL_PRELUDE + 'Transfer has failed.\u001b[0m');
                    return;
            }
        };

        const handleDaemonErrorOutput = (line) =>
            terminal.writeln(
                TERMINAL_PRELUDE + '\u001b[1m\u001b[41m' + line.replace(/(?:\r\n|\r|\n)$/im, '') + '\u001b[0m'
            );

        const handlePowerChangeEvent = (state) =>
            terminal.writeln(TERMINAL_PRELUDE + 'Server marked as ' + state + '...\u001b[0m');

        @php
            if ($user->cannot(\App\Models\Permission::ACTION_WEBSOCKET_CONNECT, $server)) {
                throw new \App\Exceptions\Http\HttpForbiddenException('You do not have permission to connect to this server\'s websocket.');
            }

            $permissions = app(\App\Services\Servers\GetUserPermissionsService::class)->handle($server, $user);

            $socket = str_replace(['https://', 'http://'], ['wss://', 'ws://'], $server->node->getConnectionAddress());
            $socket .= sprintf('/api/servers/%s/ws', $server->uuid);

            $token = app(\App\Services\Nodes\NodeJWTService::class)
                ->setExpiresAt(now()->addMinutes(10)->toImmutable())
                ->setUser($user)
                ->setClaims([
                    'server_uuid' => $server->uuid,
                    'permissions' => $permissions,
                ])
                ->handle($server->node, $user->id . $server->uuid);
        @endphp

        const socket = new WebSocket("{{ $socket }}");
        const token = '{{ $token->toString() }}';

        socket.onmessage = function(websocketMessageEvent) {
            let eventData = JSON.parse(websocketMessageEvent.data);

            if (eventData.event === 'console output' || eventData.event === 'install output') {
                handleConsoleOutput(eventData.args[0]);
            }

            if (eventData.event === 'status') {
                handlePowerChangeEvent(eventData.args[0]);
            }

            if (eventData.event === 'daemon error') {
                handleDaemonErrorOutput(eventData.args[0]);
            }

            if (eventData.event === 'stats') {
                $wire.dispatchSelf('storeStats', { data: eventData.args[0] });
            }

            if (eventData.event === 'auth success') {
                socket.send(JSON.stringify({
                    'event': 'send logs',
                    'args': [null]
                }));
            }

            // TODO: handle "token expiring" and "token expired"
        };

        socket.onopen = (event) => {
            socket.send(JSON.stringify({
                'event': 'auth',
                'args': [token]
            }));
        };

        Livewire.on('setServerState', ({ state }) => {
            socket.send(JSON.stringify({
                'event': 'set state',
                'args': [state]
            }));
        });

        Livewire.on('sendServerCommand', ({ command }) => {
            socket.send(JSON.stringify({
                'event': 'send command',
                'args': [command]
            }));
        });
    </script>
    @endscript
</x-filament::widget>
