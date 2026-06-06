@script
<script>
    Livewire.on('passkeyPropertiesValidated', async function (eventData) {
        const passkeyOptions = eventData[0].passkeyOptions;

        try {
            const passkey = await startRegistration({ optionsJSON: passkeyOptions });

            @this.call('storePasskey', JSON.stringify(passkey));
        } catch (error) {
            @this.call('registrationFailed', error.message);
        }
    });
</script>
@endscript
