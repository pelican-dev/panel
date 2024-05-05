import React, { useContext, useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import asDialog from '@/hoc/asDialog';
import { Dialog, DialogWrapperContext } from '@/components/elements/dialog';
import { Button } from '@/components/elements/button/index';
import { Input } from '@/components/elements/inputs';
import Tooltip from '@/components/elements/tooltip/Tooltip';
import disableAccountTwoFactor from '@/api/account/disableAccountTwoFactor';
import { useFlashKey } from '@/plugins/useFlash';
import { useStoreActions } from '@/state/hooks';
import FlashMessageRender from '@/components/FlashMessageRender';

const DisableTOTPDialog = () => {
    const { t } = useTranslation(['dashboard/account', 'strings']);

    const [submitting, setSubmitting] = useState(false);
    const [password, setPassword] = useState('');
    const { clearAndAddHttpError } = useFlashKey('account:two-step');
    const { close, setProps } = useContext(DialogWrapperContext);
    const updateUserData = useStoreActions((actions) => actions.user.updateUserData);

    useEffect(() => {
        setProps((state) => ({ ...state, preventExternalClose: submitting }));
    }, [submitting]);

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        e.stopPropagation();

        if (submitting) return;

        setSubmitting(true);
        clearAndAddHttpError();
        disableAccountTwoFactor(password)
            .then(() => {
                updateUserData({ useTotp: false });
                close();
            })
            .catch(clearAndAddHttpError)
            .then(() => setSubmitting(false));
    };

    return (
        <form id={'disable-totp-form'} className={'mt-6'} onSubmit={submit}>
            <FlashMessageRender byKey={'account:two-step'} className={'-mt-2 mb-6'} />
            <label className={'block pb-1'} htmlFor={'totp-password'}>
                {t('password', { ns: 'strings' })}
            </label>
            <Input.Text
                id={'totp-password'}
                type={'password'}
                variant={Input.Text.Variants.Loose}
                value={password}
                onChange={(e) => setPassword(e.currentTarget.value)}
            />
            <Dialog.Footer>
                <Button.Text onClick={close}>{t('cancel', { ns: 'strings' })}</Button.Text>
                <Tooltip
                    delay={100}
                    disabled={password.length > 0}
                    content={t<string>('password.validation.account_password')}
                >
                    <Button.Danger type={'submit'} form={'disable-totp-form'} disabled={submitting || !password.length}>
                        {t('disable', { ns: 'strings' })}
                    </Button.Danger>
                </Tooltip>
            </Dialog.Footer>
        </form>
    );
};

export default asDialog({
    title: 'Disable Two-Step Verification',
    description: 'Disabling two-step verification will make your account less secure.',
})(DisableTOTPDialog);
