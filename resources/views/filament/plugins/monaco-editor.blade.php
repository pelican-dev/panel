@script
<script>
    $wire.on('setContent', ({ content }) => {
        document.getElementById('{{ $getId() }}').editor.getModel().setValue(content);
    });

    $wire.on('setLanguage', ({ lang }) => {
        monaco.editor.setModelLanguage(document.getElementById('{{ $getId() }}').editor.getModel(), lang);
    });
</script>
@endscript

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
        monacoId: '{{ $getId() }}',

        toggleFullScreenMode() {
            this.fullScreenModeEnabled = !this.fullScreenModeEnabled;
            this.fullScreenModeEnabled ? document.body.classList.add('overflow-hidden')
                                       : document.body.classList.remove('overflow-hidden');
            $el.style.width = this.fullScreenModeEnabled ? '100vw'
                                                         : $el.parentElement.clientWidth + 'px';
        },

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
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.49.0/min/vs/loader.min.js';
            document.head.appendChild(script);
        },

        wrapPreview(value){
            return `<head>{{ $getPreviewHeadEndContent() }}</head>` +
            `&lt;body {{ $getPreviewBodyAttributes() }}&gt;` +
            `{{ $getPreviewBodyStartContent() }}` +
            `${value}` +
            `{{ $getPreviewBodyEndContent() }}` +
            `&lt;/body&gt;`;
        },

    }" x-init="
        previewContent = wrapPreview(monacoContent);
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
                require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.49.0/min/vs' }});
                let proxy = URL.createObjectURL(new Blob([` self.MonacoEnvironment = { baseUrl: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.49.0/min' }; importScripts('https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.49.0/min/vs/base/worker/workerMain.min.js');`], { type: 'text/javascript' }));
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
                            horizontalScrollbarSize: 15,
                            vertical: 'auto',
                            verticalScrollbarSize: 15
                        },
                        wordWrap: 'on',
                        WrappingIndent: 'same',
                    });
                    $el.style.zIndex = '1';
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
        <div class="flex items-center ml-auto">
            @if($getShowFullScreenToggle())
                <button type="button" aria-label="{{ __("full_screen_btn_label") }}" class="fme-full-screen-btn"
                        @click="toggleFullScreenMode()">
                    <svg class="fme-full-screen-btn-icon" x-show="!fullScreenModeEnabled"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M16 4l4 0l0 4" />
                        <path d="M14 10l6 -6" />
                        <path d="M8 20l-4 0l0 -4" />
                        <path d="M4 20l6 -6" />
                        <path d="M16 20l4 0l0 -4" />
                        <path d="M14 14l6 6" />
                        <path d="M8 4l-4 0l0 4" />
                        <path d="M4 4l6 6" />
                    </svg>
                    <svg class="fme-full-screen-btn-icon" x-show="fullScreenModeEnabled"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 9l4 0l0 -4" />
                        <path d="M3 3l6 6" />
                        <path d="M5 15l4 0l0 4" />
                        <path d="M3 21l6 -6" />
                        <path d="M19 9l-4 0l0 -4" />
                        <path d="M15 9l6 -6" />
                        <path d="M19 15l-4 0l0 4" />
                        <path d="M15 15l6 6" />
                    </svg>
                </button>
            @endif
        </div>
        <div class="fme-container" x-show="!showPreview">
            <!-- Editor -->
            <div x-show="!monacoLoader" class="fme-element-wrapper">
                <div x-ref="monacoEditorElement" class="fme-element" wire:ignore style="height: 100%"></div>
                <div x-ref="monacoPlaceholderElement" x-show="monacoPlaceholder" @click="monacoEditorFocus()"
                     :style="'font-size: ' + monacoFontSize" class="fme-placeholder"
                     x-text="monacoPlaceholderText"></div>
            </div>
        </div>
    </div>
</x-dynamic-component>
