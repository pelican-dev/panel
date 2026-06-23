import { Passkeys } from '@laravel/passkeys'

window.registerPasskey = async function (name) {
    await Passkeys.register({ name });
};

window.authenticateWithPasskey = async function () {
    try {
        const response = await Passkeys.verify();
        if (response?.redirect) {
            window.location.href = response.redirect;
        }
    } catch (error) {
        // Dismissing the OS/password-manager prompt isn't a failure worth surfacing.
        if (error?.name === 'UserCancelledError') {
            return;
        }

        // Surface the server's validation message (e.g. "Passkey not recognized")
        // instead of failing silently in the console.
        new window.FilamentNotification()
            .title(error?.message || 'Passkey authentication failed')
            .danger()
            .send();
    }
};
