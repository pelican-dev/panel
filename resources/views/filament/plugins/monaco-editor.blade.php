<x-dynamic-component :component="$getFieldWrapperView()" :field="$field" class="overflow-hidden">

    <div x-data="{
        monacoContent: $wire.$entangle('{{ $getStatePath() }}'),
        previewContent: '',
        fullScreenModeEnabled: false,
        showPreview: false,
        monacoLanguage: '{{ $getLanguage() }}',
        monacoPlaceholder: {{ (int) $getShowPlaceholder() }},
        monacoPlaceholderText: '{{ $getPlaceholderText() }}',
        monacoLoader: {{ (int) $getShowLoader() }},
        monacoFontSize: '{{ $getFontSize() }}',
        lineNumbersMinChars: {{ $getLineNumbersMinChars() }},
        automaticLayout: {{ (int) $getAutomaticLayout() }},
        monacoId: $id('monaco-editor'),

        monacoEditor(editor){
            editor.onDidChangeModelContent((e) => {
                this.monacoContent = editor.getValue();
                this.updatePlaceholder(editor.getValue());
            });

            editor.onDidBlurEditorWidget(() => {
                this.updatePlaceholder(editor.getValue());
            });

            editor.onDidFocusEditorWidget(() => {
                this.updatePlaceholder(editor.getValue());
            });
        },

        updatePlaceholder: function(value) {
            if (value == '') {
                this.monacoPlaceholder = true;
                return;
            }
            this.monacoPlaceholder = false;
        },

        monacoEditorFocus(){
            document.getElementById(this.monacoId).dispatchEvent(
                new CustomEvent('monaco-editor-focused', { monacoId: this.monacoId })
            );
        },

        monacoEditorAddLoaderScriptToHead() {
            script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min/vs/loader.min.js';
            document.head.appendChild(script);
        },

    }" x-init="
        $el.style.height = '500px';
        $watch('fullScreenModeEnabled', value => {
            if (value) {
                $el.style.height = '100vh';
            } else {
                $el.style.height = '500px';
            }
        });

        if(typeof _amdLoaderGlobal == 'undefined'){
            monacoEditorAddLoaderScriptToHead();
        }

        monacoLoaderInterval = setInterval(() => {
            if(typeof _amdLoaderGlobal !== 'undefined'){

                // Based on https://jsfiddle.net/developit/bwgkr6uq/ which works without needing service worker. Provided by loader.min.js.
                require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min/vs' }});
                let proxy = URL.createObjectURL(new Blob([` self.MonacoEnvironment = { baseUrl: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min' }; importScripts('https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min/vs/base/worker/workerMain.min.js');`], { type: 'text/javascript' }));
                window.MonacoEnvironment = { getWorkerUrl: () => proxy };

                require(['vs/editor/editor.main'], () => {

                    monaco.editor.defineTheme('custom', {{ $editorTheme() }});
                    document.getElementById(monacoId).editor = monaco.editor.create($refs.monacoEditorElement, {
                        value: monacoContent,
                        theme: localStorage.getItem('theme') === 'light' ? 'iPlastic' : 'custom',
                        fontSize: monacoFontSize,
                        lineNumbersMinChars: lineNumbersMinChars,
                        automaticLayout: automaticLayout,
                        language: monacoLanguage,
                        scrollbar: {
                            horizontal: 'auto',
                            horizontalScrollbarSize: 15
                        },

                    });
                    monacoEditor(document.getElementById(monacoId).editor);
                    document.getElementById(monacoId).addEventListener('monaco-editor-focused', (event) => {
                        document.getElementById(monacoId).editor.focus();
                    });
                    updatePlaceholder(document.getElementById(monacoId).editor.getValue());
                });

                clearInterval(monacoLoaderInterval);
                monacoLoader = false;
            }
        }, 5); " :id="monacoId"
         class="fme-wrapper"
         :class="{ 'fme-full-screen': fullScreenModeEnabled }" x-cloak>
        <div class="h-full w-full">
            <div class="fme-container" style="padding-top: 0">
                <!-- Editor -->
                <div x-show="!monacoLoader" class="fme-element-wrapper">
                    <div x-ref="monacoEditorElement" class="fme-element"></div>
                    <div x-ref="monacoPlaceholderElement" x-show="monacoPlaceholder" @click="monacoEditorFocus()" :style="'font-size: ' + monacoFontSize" class="fme-placeholder" x-text="monacoPlaceholderText"></div>
                </div>
            </div>
        </div>
    </div>

</x-dynamic-component>
