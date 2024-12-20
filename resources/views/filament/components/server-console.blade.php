<x-filament::widget>
    @assets
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@xterm/xterm/css/xterm.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@xterm/xterm/lib/xterm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@xterm/addon-fit/lib/addon-fit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@xterm/addon-web-links/lib/addon-web-links.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@xterm/addon-search/lib/addon-search.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xterm-addon-search-bar/lib/xterm-addon-search-bar.min.js"></script>
    <link rel="stylesheet" href="{{ asset('/css/filament/server/console.css') }}">
    @endassets

    <div id="terminal" wire:ignore></div>

    <div class="flex items-center w-full border-top overflow-hidden"
         style="background-color: #202A32; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;">
        <x-filament::icon
            icon="tabler-chevrons-right"
        />
        <input
            class="w-full focus:outline-none focus:ring-0 border-none"
            style="background-color: #202A32;"
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
            rows: 30,
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
            terminal.writeln(TERMINAL_PRELUDE + '\u001b[1m\u001b[41m' + line.replace(/(?:\r\n|\r|\n)$/im, '') + '\u001b[0m');

        const handlePowerChangeEvent = (state) =>
            terminal.writeln(TERMINAL_PRELUDE + 'Server marked as ' + state + '...\u001b[0m');

        const socket = new WebSocket("{{ $this->getSocket() }}");
        let token = '{{ $this->getToken() }}';

        socket.onmessage = function(websocketMessageEvent) {
            let { event, args } = JSON.parse(websocketMessageEvent.data);

            switch (event) {
                case 'console output':
                case 'install output':
                    handleConsoleOutput(args[0]);
                    break;
                case 'status':
                    handlePowerChangeEvent(args[0]);

                    $wire.dispatch('console-status', { state: args[0] });
                    break;
                case 'transfer status':
                    handleTransferStatus(args[0]);
                    break;
                case 'daemon error':
                    handleDaemonErrorOutput(args[0]);
                    break;
                case 'stats':
                    $wire.dispatchSelf('store-stats', { data: args[0] });
                    break;
                case 'auth success':
                    socket.send(JSON.stringify({
                        'event': 'send logs',
                        'args': [null]
                    }));
                    break;
                case 'install started':
                    $wire.dispatch('console-install-started');
                    break;
                case 'install completed':
                    $wire.dispatch('console-install-completed');
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

        $wire.$on('sendServerCommand', ({ command }) => {
            socket.send(JSON.stringify({
                'event': 'send command',
                'args': [command]
            }));
        });
    </script>
    @endscript
</x-filament::widget>
