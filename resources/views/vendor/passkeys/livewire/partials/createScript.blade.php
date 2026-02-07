@script
<script>
    Livewire.on('passkeyPropertiesValidated', async function (eventData) {
        const passkeyOptions = eventData[0].passkeyOptions;

        const passkey = await startRegistration({ optionsJSON: passkeyOptions });

        @this.call('storePasskey', JSON.stringify(passkey));
    });
</script>
@endscript
