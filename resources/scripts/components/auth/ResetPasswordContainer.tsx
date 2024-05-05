import React, { useState } from 'react';
import { RouteComponentProps } from 'react-router';
import { Link } from 'react-router-dom';
import performPasswordReset from '@/api/auth/performPasswordReset';
import { httpErrorToHuman } from '@/api/http';
import LoginFormContainer from '@/components/auth/LoginFormContainer';
import { Actions, useStoreActions } from 'easy-peasy';
import { ApplicationStore } from '@/state';
import { Formik, FormikHelpers } from 'formik';
import { object, ref, string } from 'yup';
import { useTranslation } from 'react-i18next';
import Field from '@/components/elements/Field';
import Input from '@/components/elements/Input';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';

interface Values {
    password: string;
    passwordConfirmation: string;
}

export default ({ match, location }: RouteComponentProps<{ token: string }>) => {
    const { t } = useTranslation('auth');

    const [email, setEmail] = useState('');

    const { clearFlashes, addFlash } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);

    const parsed = new URLSearchParams(location.search);
    if (email.length === 0 && parsed.get('email')) {
        setEmail(parsed.get('email') || '');
    }

    const submit = ({ password, passwordConfirmation }: Values, { setSubmitting }: FormikHelpers<Values>) => {
        clearFlashes();
        performPasswordReset(email, { token: match.params.token, password, passwordConfirmation })
            .then(() => {
                // @ts-expect-error this is valid
                window.location = '/';
            })
            .catch((error) => {
                console.error(error);

                setSubmitting(false);
                addFlash({ type: 'error', title: 'Error', message: httpErrorToHuman(error) });
            });
    };

    return (
        <Formik
            onSubmit={submit}
            initialValues={{
                password: '',
                passwordConfirmation: '',
            }}
            validationSchema={object().shape({
                password: string()
                    .required(t('reset_password.required.password'))
                    .min(8, t('reset_password.validation.password')),
                passwordConfirmation: string()
                    .required(t('reset_password.required.password_confirmation'))
                    // @ts-expect-error this is valid
                    .oneOf([ref('password'), null], t('reset_password.validation.password_confirmation')),
            })}
        >
            {({ isSubmitting }) => (
                <LoginFormContainer title={t('reset_password.title')} css={tw`w-full flex`}>
                    <div>
                        <label>{t('email')}</label>
                        <Input value={email} isLight disabled />
                    </div>
                    <div css={tw`mt-6`}>
                        <Field
                            light
                            label={t('reset_password.new_password')}
                            name={'password'}
                            type={'password'}
                            description={t('reset_password.requirement.password')}
                        />
                    </div>
                    <div css={tw`mt-6`}>
                        <Field
                            light
                            label={t('reset_password.confirm_new_password')}
                            name={'passwordConfirmation'}
                            type={'password'}
                        />
                    </div>
                    <div css={tw`mt-6`}>
                        <Button size={'xlarge'} type={'submit'} disabled={isSubmitting} isLoading={isSubmitting}>
                            {t('reset_password.button')}
                        </Button>
                    </div>
                    <div css={tw`mt-6 text-center`}>
                        <Link
                            to={'/auth/login'}
                            css={tw`text-xs text-neutral-500 tracking-wide no-underline uppercase hover:text-neutral-600`}
                        >
                            {t('return_to_login')}
                        </Link>
                    </div>
                </LoginFormContainer>
            )}
        </Formik>
    );
};
