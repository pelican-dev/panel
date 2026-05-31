import {
    browserSupportsWebAuthn,
    startAuthentication,
    startRegistration,
} from '@simplewebauthn/browser'

window.browserSupportsWebAuthn = browserSupportsWebAuthn;
window.startAuthentication = startAuthentication;
window.startRegistration = startRegistration;

window.authenticateWithPasskey = async function () {
    if (!browserSupportsWebAuthn()) {
        return;
    }

    try {
        const optionsResponse = await fetch('/auth/passkeys/authentication-options', {
            headers: { 'Accept': 'application/json' },
        });

        if (!optionsResponse.ok) {
            throw new Error('Could not get authentication options: ' + optionsResponse.status);
        }

        const options = await optionsResponse.json();
        const credential = await startAuthentication({ optionsJSON: options });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/auth/passkeys/authenticate';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = csrfToken;
        form.appendChild(csrf);

        const passkeyInput = document.createElement('input');
        passkeyInput.type = 'hidden';
        passkeyInput.name = 'start_authentication_response';
        passkeyInput.value = JSON.stringify(credential);
        form.appendChild(passkeyInput);

        document.body.appendChild(form);
        form.submit();
    } catch (error) {
        console.error('Passkey authentication failed:', error);
    }
};
