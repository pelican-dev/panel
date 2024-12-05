<x-filament::widget>
    @assets
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/xterm/css/xterm.css">
    <script src="https://cdn.jsdelivr.net/npm/xterm/lib/xterm.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xterm-addon-fit/lib/xterm-addon-fit.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xterm-addon-web-links/lib/xterm-addon-web-links.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xterm-addon-search/lib/xterm-addon-search.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xterm-addon-search-bar@0.2.0/lib/xterm-addon-search-bar.min.js"></script>
    <style>
        #terminal {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .xterm .xterm-rows > div {
            padding-left: 10px;
            padding-top: 2px;
            padding-right: 10px;
        }
    </style>
    @endassets

    <div id="terminal" wire:ignore></div>

    <div class="flex items-center w-full bg-transparent border rounded">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-right">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M7 7l5 5l-5 5" />
            <path d="M13 7l5 5l-5 5" />
        </svg>
        <input
            class="w-full bg-transparent p-2 focus:outline-none focus:ring-0 border-none"
            type="text"
            title="{{ $this->canSendCommand() ? '' : 'Can\'t send command when the server is Offline' }}"
            :readonly="{{ !$this->canSendCommand() }}"
            placeholder="Type a command..."
            wire:model="input"
            wire:keydown.enter="enter"
            wire:keydown.up.prevent="up"
            wire:keydown.down="down"
        >
    </div>

    @script
    <script>
        let theme = {
            background: 'rgba(19,26,32,0.7)',
            cursor: 'transparent',
            black: '#000000',
            red: '#E54B4B',
            green: '#9ECE58',
            yellow: '#FAED70',
            blue: '#396FE2',
            magenta: '#BB80B3',
            cyan: '#2DDAFD',
            white: '#d0d0d0',
            brightBlack: 'rgba(255, 255, 255, 0.2)',
            brightRed: '#FF5370',
            brightGreen: '#C3E88D',
            brightYellow: '#FFCB6B',
            brightBlue: '#82AAFF',
            brightMagenta: '#C792EA',
            brightCyan: '#89DDFF',
            brightWhite: '#ffffff',
            selection: '#FAF089'
        };

        let options = {
            fontSize: 16,
            disableStdin: true,
            cursorStyle: 'underline',
            cursorInactiveStyle: 'none',
            allowTransparency: true,
            letterSpacing: 0.75,
            lineHeight: 1,
            rows: 35,
            cols: 110,
            theme: theme
        };

        const terminal = new Terminal(options);
        const fitAddon = new FitAddon.FitAddon();
        const webLinksAddon = new WebLinksAddon.WebLinksAddon();
        const searchAddon = new SearchAddon.SearchAddon();
        const searchAddonBar = new SearchBarAddon.SearchBarAddon({ searchAddon });

        terminal.loadAddon(fitAddon);
        terminal.loadAddon(webLinksAddon);
        terminal.loadAddon(searchAddon);
        terminal.loadAddon(searchAddonBar);


        terminal.open(document.getElementById('terminal'));

        fitAddon.fit(); //Fit on first load

        window.addEventListener('resize', () => {
            fitAddon.fit();
        });

        terminal.attachCustomKeyEventHandler((event) => {
            if ((event.ctrlKey || event.metaKey) && event.key === 'c') {
                document.execCommand('copy'); // navigator.clipboard.writeText() only works on ssl..
                return false;
            } else if ((event.ctrlKey || event.metaKey) && event.key === 'f') {
                event.preventDefault();
                searchAddonBar.show();
                return false;
            } else if (event.key === 'Escape') {
                searchAddonBar.hidden();
            }
            return true;
        });

        const TERMINAL_PRELUDE = '\u001b[1m\u001b[33mpelican@' + '{{ \Filament\Facades\Filament::getTenant()->name }}' + ' ~ \u001b[0m';

        const handleConsoleOutput = (line, prelude = false) =>
            terminal.writeln((prelude ? TERMINAL_PRELUDE : '') + line.replace(/(?:\r\n|\r|\n)$/im, '') + '\u001b[0m');

        const handleTransferStatus = (status) =>
            status === 'failure' && terminal.writeln(TERMINAL_PRELUDE + 'Transfer has failed.\u001b[0m');

        const handleDaemonErrorOutput = (line) =>
            terminal.writeln(
                TERMINAL_PRELUDE + '\u001b[1m\u001b[41m' + line.replace(/(?:\r\n|\r|\n)$/im, '') + '\u001b[0m'
            );

        const handlePowerChangeEvent = (state) =>
            terminal.writeln(TERMINAL_PRELUDE + 'Server marked as ' + state + '...\u001b[0m');

        const socket = new WebSocket("{{ $this->getSocket() }}");
        let token = '{{ $this->getToken() }}';

        socket.onmessage = function(websocketMessageEvent) {
            let {event, args} = JSON.parse(websocketMessageEvent.data);

            switch (event) {
                case 'console output':
                case 'install output':
                    handleConsoleOutput(args[0]);
                    break;
                case 'status':
                    handlePowerChangeEvent(args[0]);
                    break;
                case 'transfer status':
                    handleTransferStatus(args[0]);
                    break;
                case 'daemon error':
                    handleDaemonErrorOutput(args[0]);
                    break;
                case 'stats':
                    $wire.dispatchSelf('storeStats', { data: args[0] });
                    break;
                case 'auth success':
                    socket.send(JSON.stringify({
                        'event': 'send logs',
                        'args': [null]
                    }));
                    break;
                case 'token expiring':
                case 'token expired':
                    token = '{{ $this->getToken() }}';

                    socket.send(JSON.stringify({
                        'event': 'auth',
                        'args': [token]
                    }));
                    break;
            }
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
