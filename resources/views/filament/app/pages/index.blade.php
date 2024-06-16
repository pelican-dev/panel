<x-filament-panels::page>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xterm/5.5.0/xterm.js" integrity="sha512-Gujw5GajF5is3nMoGv9X+tCMqePLL/60qvAv1LofUZTV9jK8ENbM9L+maGmOsNzuZaiuyc/fpph1KT9uR5w3CQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/xterm/5.5.0/xterm.css" integrity="sha512-AbNrj/oSHJaILgcdnkYm+DQ08SqVbZ8jlkJbFyyS1WDcAaXAcAfxJnCH69el7oVgTwVwyA5u5T+RdFyUykrV3Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div id="terminal"></div>
    <script>
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

</x-filament-panels::page>
