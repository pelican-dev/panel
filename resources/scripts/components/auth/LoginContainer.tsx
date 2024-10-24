import React, { useEffect, useState } from 'react';
import { Link, RouteComponentProps } from 'react-router-dom';
import login from '@/api/auth/login';
import LoginFormContainer from '@/components/auth/LoginFormContainer';
import { useStoreState } from 'easy-peasy';
import { Formik, FormikHelpers } from 'formik';
import { object, string } from 'yup';
import { useTranslation } from 'react-i18next';
import Field from '@/components/elements/Field';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';
import Turnstile, { useTurnstile } from 'react-turnstile';
import useFlash from '@/plugins/useFlash';

interface Values {
    username: string;
    password: string;
}

const LoginContainer = ({ history }: RouteComponentProps) => {
    const { t } = useTranslation(['auth', 'strings']);

    const turnstile = useTurnstile();
    const [token, setToken] = useState('');

    const { clearFlashes, clearAndAddHttpError, addError } = useFlash();
    const { enabled: recaptchaEnabled, siteKey } = useStoreState((state) => state.settings.data!.recaptcha);

    useEffect(() => {
        clearFlashes();
    }, []);

    const onSubmit = (values: Values, { setSubmitting }: FormikHelpers<Values>) => {
        clearFlashes();

        if (recaptchaEnabled && !token) {
            addError({ message: 'No captcha token found.' });

            setSubmitting(false);
            return;
        }

        login({ ...values, recaptchaData: token })
            .then((response) => {
                if (response.complete) {
                    // @ts-expect-error this is valid
                    window.location = response.intended || '/';
                    return;
                }

                history.replace('/auth/login/checkpoint', { token: response.confirmationToken });
            })
            .catch((error) => {
                console.error(error);

                setToken('');
                turnstile.reset();

                setSubmitting(false);
                clearAndAddHttpError({ error });
            });
    };

    return (
        <Formik
            onSubmit={onSubmit}
            initialValues={{ username: '', password: '' }}
            validationSchema={object().shape({
                username: string().required(t('login.required.username_or_email')),
                password: string().required(t('login.required.password')),
            })}
        >
            {({ isSubmitting, setSubmitting }) => (
                <LoginFormContainer title={t('login.title')} css={tw`w-full flex`}>
                    <Field
                        light
                        type={'text'}
                        label={t('user_identifier', { ns: 'strings' })}
                        name={'username'}
                        disabled={isSubmitting}
                    />
                    <div css={tw`mt-6`}>
                        <Field
                            light
                            type={'password'}
                            label={t('password', { ns: 'strings' })}
                            name={'password'}
                            disabled={isSubmitting}
                        />
                    </div>
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
                        <Button type={'submit'} size={'xlarge'} isLoading={isSubmitting} disabled={isSubmitting}>
                            {t('login.button')}
                        </Button>
                    </div>
                    <div css={tw`mt-6 text-center`}>
                        <Link
                            to={'/auth/password'}
                            css={tw`text-xs text-neutral-500 tracking-wide no-underline uppercase hover:text-neutral-600`}
                        >
                            {t('forgot_password.label')}
                        </Link>
                    </div>
                </LoginFormContainer>
            )}
        </Formik>
    );
};

export default LoginContainer;
