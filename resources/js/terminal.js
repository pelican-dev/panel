import { Terminal } from 'xterm';
import { FitAddon } from '@xterm/addon-fit';
import { WebLinksAddon } from '@xterm/addon-web-links';
import { SearchAddon } from '@xterm/addon-search';
import { SearchBarAddon } from 'xterm-addon-search-bar';

export function initializeTerminal({ elId = 'terminal', options = {}, socketUrl, onEvent = () => {} }) {
    const terminal = new Terminal(options);

    const fitAddon = new FitAddon();
    const webLinksAddon = new WebLinksAddon();
    const searchAddon = new SearchAddon();
    const searchBarAddon = new SearchBarAddon({ searchAddon });

    terminal.loadAddon(fitAddon);
    terminal.loadAddon(webLinksAddon);
    terminal.loadAddon(searchAddon);
    terminal.loadAddon(searchBarAddon);

    terminal.open(document.getElementById(elId));
    fitAddon.fit();

    window.addEventListener('resize', () => fitAddon.fit());

    terminal.attachCustomKeyEventHandler((event) => {
        if ((event.ctrlKey || event.metaKey) && event.key === 'c') {
            document.execCommand('copy');
            return false;
        } else if ((event.ctrlKey || event.metaKey) && event.key === 'f') {
            event.preventDefault();
            searchBarAddon.show();
            return false;
        } else if (event.key === 'Escape') {
            searchBarAddon.hidden();
        }
        return true;
    });

    // WebSocket connection handling
    const socket = new WebSocket(socketUrl);

    socket.onerror = (event) => {
        $wire.dispatchSelf('websocket-error');
    };

    socket.onmessage = function(websocketMessageEvent) {
        let { event, args } = JSON.parse(websocketMessageEvent.data);

        switch (event) {
            case 'console output':
            case 'install output':
                terminal.writeln(args[0]);
                break;
            case 'feature match':
                Livewire.dispatch('mount-feature', { data: args[0] });
                break;
            case 'status':
                handlePowerChangeEvent(args[0]);
                $wire.dispatch('console-status', { state: args[0] });
                break;
            case 'transfer status':
                terminal.writeln(TERMINAL_PRELUDE + 'Transfer has failed.\u001b[0m');
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
            case 'token expiring':
            case 'token expired':
                $wire.dispatchSelf('token-request');
                break;
        }
    };

    socket.onopen = () => {
        $wire.dispatchSelf('token-request');
    };

    // Handle events
    const TERMINAL_PRELUDE = '\u001b[1m\u001b[33mpelican@' + '{{ \Filament\Facades\Filament::getTenant()->name }}' + ' ~ \u001b[0m';

    const handleConsoleOutput = (line, prelude = false) =>
        terminal.writeln((prelude ? TERMINAL_PRELUDE : '') + line.replace(/(?:\r\n|\r|\n)$/im, '') + '\u001b[0m');

    const handleDaemonErrorOutput = (line) =>
        terminal.writeln(TERMINAL_PRELUDE + '\u001b[1m\u001b[41m' + line.replace(/(?:\r\n|\r|\n)$/im, '') + '\u001b[0m');

    const handlePowerChangeEvent = (state) =>
        terminal.writeln(TERMINAL_PRELUDE + 'Server marked as ' + state + '...\u001b[0m');

    // Return terminal, fitAddon, searchAddon, searchBarAddon for further usage
    return {
        terminal,
        fitAddon,
        searchAddon,
        searchBarAddon,
        socket
    };
}
