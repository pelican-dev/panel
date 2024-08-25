<x-filament-panels::page>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xterm/5.5.0/xterm.js" integrity="sha512-Gujw5GajF5is3nMoGv9X+tCMqePLL/60qvAv1LofUZTV9jK8ENbM9L+maGmOsNzuZaiuyc/fpph1KT9uR5w3CQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/xterm/5.5.0/xterm.css" integrity="sha512-AbNrj/oSHJaILgcdnkYm+DQ08SqVbZ8jlkJbFyyS1WDcAaXAcAfxJnCH69el7oVgTwVwyA5u5T+RdFyUykrV3Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div>
        <div id="terminal" wire:ignore></div>
        <script>
            // https://xtermjs.org/docs/api/terminal/interfaces/iterminaloptions/
            let options = {
                fontSize: 18,
                // fontFamily: th('fontFamily.mono'),
                disableStdin: true,
                cursorStyle: 'underline',
                allowTransparency: true,
                rows: 20,
                cols: 110,
                // theme: theme,
            };

            const terminal = new Terminal(options);
            const TERMINAL_PRELUDE = '\u001b[1m\u001b[33mpelican@' + '{{ \Filament\Facades\Filament::getTenant()->name }}' + ' ~ \u001b[0m';
            terminal.open(document.getElementById('terminal'));

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

        </script>

        <div>
            <input
                class="w-full bg-transparent"
                type="text"
                placeholder="Type a command..."
                wire:model="input"
                wire:keydown.enter="enter"
                wire:keydown.up="up"
                wire:keydown.down="down"
            >
        </div>


    </div>

    @script
    <script>
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


        socket.onmessage = function (websocketMessageEvent) {
            let eventData = JSON.parse(websocketMessageEvent.data);

            if (eventData.event === 'console output') {
                handleConsoleOutput(eventData.args[0]);
            }

            if (eventData.event === 'status') {
                handlePowerChangeEvent(eventData.args[0]);
            }

            if (eventData.event === 'daemon error') {
                handleDaemonErrorOutput(eventData.args[0]);
            }

            if (eventData.event === 'stats') {
                // TODO: store and show stats

                // {"event":"stats","args":["{\"memory_bytes\":2382733312,\"memory_limit_bytes\":25204965376,\"cpu_absolute\":40.529,\"network\":{\"rx_bytes\":22302231,\"tx_bytes\":7138264},\"uptime\":129543658,\"state\":\"running\",\"disk_bytes\":3500798875}"]}
            }

            if (eventData.event === 'auth success') {
                socket.send(JSON.stringify({
                    "event": "send logs",
                    "args": [null]
                }));
            }
        };

        // {"event":"","args":["[S_API FAIL] Tried to access Steam interface SteamUser021 before SteamAPI_Init succeeded."]}
        // {"event":"send command","args":["hello!"]}

        socket.onopen = (event) => {
            socket.send(JSON.stringify({
                "event": "auth",
                "args": [token]
            }));
        };

        Livewire.on('setServerState', ({ state }) => {
            socket.send(JSON.stringify({
                "event": "set state",
                "args": [state]
            }));
        });

        Livewire.on('sendServerCommand', ({ command }) => {
            socket.send(JSON.stringify({
                "event": "send command",
                "args": [command]
            }));
        });
    </script>
    @endscript
    <x-filament-panels::form>
        {{ $this->form }}
    </x-filament-panels::form>
</x-filament-panels::page>
