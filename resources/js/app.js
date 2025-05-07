import './bootstrap';
import { initializeTerminal } from './terminal';  // Import the terminal initialization function

document.addEventListener('DOMContentLoaded', () => {
    // Access the PHP variables from window.phpData
    const { userFont, userFontSize, userRows, socketUrl } = window.phpData;

    const terminalOptions = {
        fontSize: userFontSize,
        fontFamily: `${userFont}, monospace`,
        lineHeight: 1.2,
        disableStdin: true,
        cursorStyle: 'underline',
        cursorInactiveStyle: 'underline',
        allowTransparency: true,
        rows: userRows,
        theme: {
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
        }
    };

    // Initialize the terminal with the options and WebSocket URL
    const { terminal, fitAddon, searchAddon, searchBarAddon, socket } = initializeTerminal({
        elId: 'terminal',
        options: terminalOptions,
        socketUrl: socketUrl,
        onEvent: (event, args) => {
            switch (event) {
                case 'status':
                    console.log('Status event received:', args);
                    break;
                case 'transfer status':
                    terminal.writeln(TERMINAL_PRELUDE + 'Transfer status: ' + args);
                    break;
                case 'daemon error':
                    terminal.writeln(TERMINAL_PRELUDE + 'Daemon error: ' + args);
                    break;
                default:
                    break;
            }
        }
    });
});
