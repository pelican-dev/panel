import { Passkeys } from '@laravel/passkeys'

window.registerPasskey = async function (name) {
    await Passkeys.register({ name });
};

window.authenticateWithPasskey = async function () {
    const response = await Passkeys.verify();
    if (response?.redirect) {
        window.location.href = response.redirect;
    }
};
