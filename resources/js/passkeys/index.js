import {
    browserSupportsWebAuthn,
    startAuthentication,
    startRegistration,
} from '@simplewebauthn/browser'

window.registerPasskey = async function (name) {
    const optionsResponse = await fetch('/user/passkeys/options', {
        headers: { 'Accept': 'application/json' },
    });

    if (!optionsResponse.ok) {
        throw new Error('Could not get registration options: ' + optionsResponse.status);
    }

    const options = await optionsResponse.json();
    const credential = await startRegistration({ optionsJSON: options });

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    const storeResponse = await fetch('/user/passkeys', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ name, credential }),
    });

    if (!storeResponse.ok) {
        const data = await storeResponse.json().catch(() => ({}));
        throw new Error(data.message ?? 'Failed to register passkey');
    }
};

window.authenticateWithPasskey = async function () {
    if (!browserSupportsWebAuthn()) {
        return;
    }

    try {
        const optionsResponse = await fetch('/passkeys/login/options', {
            headers: { 'Accept': 'application/json' },
        });

        if (!optionsResponse.ok) {
            throw new Error('Could not get authentication options: ' + optionsResponse.status);
        }

        const options = await optionsResponse.json();
        const credential = await startAuthentication({ optionsJSON: options });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        const response = await fetch('/passkeys/login', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ credential }),
        });

        if (response.ok) {
            const data = await response.json();
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        }
    } catch (error) {
        console.error('Passkey authentication failed:', error);
    }
};
