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

            // create websocket instance
            var socket = new WebSocket("wss://example.com:8080/api/servers/ef722e2f-9b9b-4962-97ed-719d656827c9/ws");
            var token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImp0aSI6ImI2N2Q5YzY5MmZkZTZmNmZhMzVlNzliNWFiNTM0ZDJkIn0.eyJpc3MiOiJodHRwczovL3BhbmVsLnRlc3QiLCJhdWQiOlsiaHR0cHM6Ly9leGFtcGxlLmNvbTo4MDgwIl0sImp0aSI6ImI2N2Q5YzY5MmZkZTZmNmZhMzVlNzliNWFiNTM0ZDJkIiwiaWF0IjoxNzE4NTY5NzQ5LCJuYmYiOjE3MTg1Njk0NDksImV4cCI6MTcxODU3MDM0OSwic2VydmVyX3V1aWQiOiJlZjcyMmUyZi05YjliLTQ5NjItOTdlZC03MTlkNjU2ODI3YzkiLCJwZXJtaXNzaW9ucyI6WyIqIiwiYWRtaW4ud2Vic29ja2V0LmVycm9ycyIsImFkbWluLndlYnNvY2tldC5pbnN0YWxsIiwiYWRtaW4ud2Vic29ja2V0LnRyYW5zZmVyIl0sInVzZXJfdXVpZCI6IjhhMGEzYzcwLTRhZWMtNDRhOC1iYzc5LTliY2IxNmY0NTc1MSIsInVzZXJfaWQiOjEsInVuaXF1ZV9pZCI6Ik0ycm9CY21iNTZubHdneWMifQ.9F7nwoeK0s08t8fGLzwSyM56M5pclC-dJe5_R5kbeYM';

            // add event listener reacting when message is received
            socket.onmessage = function (event) {
                terminal.write(TERMINAL_PRELUDE + event.data);
            };

            socket.send('auth', token);

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
