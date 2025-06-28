import { Terminal } from '@xterm/xterm';
import { FitAddon } from '@xterm/addon-fit';
import { WebLinksAddon } from '@xterm/addon-web-links';
import { SearchAddon } from '@xterm/addon-search';
import { SearchBarAddon } from 'xterm-addon-search-bar';

window.Xterm = {
    Terminal,
    FitAddon,
    WebLinksAddon,
    SearchAddon,
    SearchBarAddon,
};
