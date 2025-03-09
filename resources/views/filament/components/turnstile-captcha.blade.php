@php
    $statePath = $getStatePath();
    $fieldWrapperView = $getFieldWrapperView();
@endphp

<x-dynamic-component class="flex justify-center" :component="$fieldWrapperView" :field="$turnstile">
    <div x-data="{
            state: $wire.entangle('{{ $statePath }}').defer
        }"
         wire:ignore
         x-init="(() => {
            let options= {
                callback: function (token) {
                    $wire.set('{{ $statePath }}', token)
                },

                errorCallback: function () {
                    $wire.set('{{ $statePath }}', null)
                },
            }

            window.onloadTurnstileCallback = () => {
                turnstile.render($refs.turnstile, options)
            }

            resetCaptcha = () => {
                turnstile.reset($refs.turnstile)
            }
        })()"
    >
        <div data-sitekey="{{config('captcha.turnstile.site_key')}}" x-ref="turnstile"></div>
    </div>

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback" defer></script>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('reset-captcha', (event) => {
                    resetCaptcha();
                });
            });
        </script>
    @endpush
</x-dynamic-component>
