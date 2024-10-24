import * as React from 'react';
import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import requestPasswordResetEmail from '@/api/auth/requestPasswordResetEmail';
import { httpErrorToHuman } from '@/api/http';
import LoginFormContainer from '@/components/auth/LoginFormContainer';
import { useStoreState } from 'easy-peasy';
import Field from '@/components/elements/Field';
import { Formik, FormikHelpers } from 'formik';
import { object, string } from 'yup';
import { useTranslation } from 'react-i18next';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';
import Turnstile, { useTurnstile } from 'react-turnstile';
import useFlash from '@/plugins/useFlash';

interface Values {
    email: string;
}

export default () => {
    const { t } = useTranslation('auth');

    const turnstile = useTurnstile();
    const [token, setToken] = useState('');

    const { clearFlashes, addFlash, addError } = useFlash();
    const { enabled: recaptchaEnabled, siteKey } = useStoreState((state) => state.settings.data!.recaptcha);

    useEffect(() => {
        clearFlashes();
    }, []);

    const handleSubmission = ({ email }: Values, { setSubmitting, resetForm }: FormikHelpers<Values>) => {
        clearFlashes();

        if (recaptchaEnabled && !token) {
            addError({ message: 'No captcha token found.' });

            setSubmitting(false);
            return;
        }

        requestPasswordResetEmail(email, token)
            .then((response) => {
                resetForm();
                addFlash({ type: 'success', title: 'Success', message: response });
            })
            .catch((error) => {
                console.error(error);
                addFlash({ type: 'error', title: 'Error', message: httpErrorToHuman(error) });
            })
            .then(() => {
                setToken('');
                turnstile.reset();

                setSubmitting(false);
            });
    };

    return (
        <Formik
            onSubmit={handleSubmission}
            initialValues={{ email: '' }}
            validationSchema={object().shape({
                email: string()
                    .email(t('forgot_password.required.email'))
                    .required(t('forgot_password.required.email')),
            })}
        >
            {({ isSubmitting, setSubmitting }) => (
                <LoginFormContainer title={t('forgot_password.title')} css={tw`w-full flex`}>
                    <Field
                        light
                        label={'Email'}
                        description={t('forgot_password.label_help')}
                        name={'email'}
                        type={'email'}
                    />
                    {recaptchaEnabled && (
                        <Turnstile
                            sitekey={siteKey || '_invalid_key'}
                            className='mt-6 flex justify-center'
                            retry='never'
                            onVerify={(token) => {
                                setToken(token);
                            }}
                            onError={(error) => {
                                console.error('Error verifying captcha: ' + error);
                                addError({ message: 'Error verifying captcha: ' + error });

                                setSubmitting(false);
                                setToken('');
                            }}
                            onExpire={() => {
                                setSubmitting(false);
                                setToken('');
                            }}
                        />
                    )}
                    <div css={tw`mt-6`}>
                        <Button type={'submit'} size={'xlarge'} disabled={isSubmitting} isLoading={isSubmitting}>
                            {t('forgot_password.button')}
                        </Button>
                    </div>
                    <div css={tw`mt-6 text-center`}>
                        <Link
                            to={'/auth/login'}
                            css={tw`text-xs text-neutral-500 tracking-wide uppercase no-underline hover:text-neutral-700`}
                        >
                            {t('return_to_login')}
                        </Link>
                    </div>
                </LoginFormContainer>
            )}
        </Formik>
    );
};
