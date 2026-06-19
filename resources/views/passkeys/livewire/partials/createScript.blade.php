@script
<script>
    window.onClickCreatePasskey = async function () {
        try {
            await window.registerPasskey(@this.name);
            @this.call('onPasskeyRegistered');
        } catch (error) {
            @this.call('registrationFailed', error.message);
        }
    };
</script>
@endscript
