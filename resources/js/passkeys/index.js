import { Passkeys } from '@laravel/passkeys'

window.registerPasskey = async function (name) {
    await Passkeys.register({ name });
};

window.authenticateWithPasskey = async function () {
    await Passkeys.verify();
};
