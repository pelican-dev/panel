<x-filament-panels::page>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xterm/5.5.0/xterm.js" integrity="sha512-Gujw5GajF5is3nMoGv9X+tCMqePLL/60qvAv1LofUZTV9jK8ENbM9L+maGmOsNzuZaiuyc/fpph1KT9uR5w3CQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/xterm/5.5.0/xterm.css" integrity="sha512-AbNrj/oSHJaILgcdnkYm+DQ08SqVbZ8jlkJbFyyS1WDcAaXAcAfxJnCH69el7oVgTwVwyA5u5T+RdFyUykrV3Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div id="terminal"></div>
    <script>
        // https://xtermjs.org/docs/api/terminal/interfaces/iterminaloptions/
        let options = {
            fontSize: 18,
            // fontFamily: th('fontFamily.mono'),
            disableStdin: true,
            cursorStyle: 'underline',
            allowTransparency: true,
            rows: 20,
            // theme: theme,
        };

        const terminal = new Terminal(options);
        const TERMINAL_PRELUDE = '\u001b[1m\u001b[33mpelican@' + '{{ \Filament\Facades\Filament::getTenant()->name }}' + ' ~ \u001b[0m';

        terminal.open(document.getElementById('terminal'));
        terminal.write(TERMINAL_PRELUDE);
    </script>

    <div>
        <input
            style="color: black;"
            type="text"
            placeholder="Type a command..."
            wire:keydown.enter="enter"
            wire:keydown.up="up"
            wire:keydown.down="down"
        >
    </div>

    <script>
        window.addEventListener("load", function() {

            @php
                /** @var \App\Models\Server $server */
                $server = \Filament\Facades\Filament::getTenant();
                $user = auth()->user();

                if ($user->cannot(\App\Models\Permission::ACTION_WEBSOCKET_CONNECT, $server)) {
                    throw new \App\Exceptions\Http\HttpForbiddenException('You do not have permission to connect to this server\'s websocket.');
                }

                $permissions = app(\App\Services\Servers\GetUserPermissionsService::class)->handle($server, $user);

                $socket = str_replace(['https://', 'http://'], ['wss://', 'ws://'], $server->node->getConnectionAddress());
                $socket .= sprintf('/api/servers/%s/ws', $server->uuid);

                $token = app(\App\Services\Nodes\NodeJWTService::class)
                    ->setExpiresAt(\Carbon\CarbonImmutable::now()->addMinutes(10))
                    ->setUser($user)
                    ->setClaims([
                        'server_uuid' => $server->uuid,
                        'permissions' => $permissions,
                    ])
                    ->handle($server->node, $user->id . $server->uuid);
            @endphp

            var socket = new WebSocket("{{ $socket }}");
            var token = '{{ $token->toString() }}';


            socket.onmessage = function (event) {
                terminal.write(TERMINAL_PRELUDE + event.data);
            };

            socket.onopen = (event) => {
                socket.send(JSON.stringify({
                    "event": "auth",
                    "args": [token]
                }));
            };


            // var form = document.getElementsByClassName("foo");
            // var input = document.getElementById("input");
            // form[0].addEventListener("submit", function (e) {
            //     // on forms submission send input to our server
            //     input_text = input.value;
            //     socket.send(input_text);
            //     e.preventDefault()
            // })
        });
    </script>

</x-filament-panels::page>
