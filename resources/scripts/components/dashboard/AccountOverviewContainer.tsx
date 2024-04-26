import * as React from 'react';
import ContentBox from '@/components/elements/ContentBox';
import UpdatePasswordForm from '@/components/dashboard/forms/UpdatePasswordForm';
import UpdateEmailAddressForm from '@/components/dashboard/forms/UpdateEmailAddressForm';
import ConfigureTwoFactorForm from '@/components/dashboard/forms/ConfigureTwoFactorForm';
import PageContentBlock from '@/components/elements/PageContentBlock';
import tw from 'twin.macro';
import { breakpoint } from '@/theme';
import styled from 'styled-components/macro';
import MessageBox from '@/components/MessageBox';
import { useLocation } from 'react-router-dom';
import { useTranslation } from 'react-i18next';

const Container = styled.div`
    ${tw`flex flex-wrap`};

    & > div {
        ${tw`w-full`};

        ${breakpoint('sm')`
      width: calc(50% - 1rem);
    `}

        ${breakpoint('md')`
      ${tw`w-auto flex-1`};
    `}
    }
`;

export default () => {
    const { t } = useTranslation('dashboard/account');
    const { state } = useLocation<undefined | { twoFactorRedirect?: boolean }>();

    return (
        <PageContentBlock title={t('title')}>
            {state?.twoFactorRedirect && (
                <MessageBox title={t('two_factor.required.title')} type={'error'}>
                    {t('two_factor.required.description')}
                </MessageBox>
            )}

            <Container css={[tw`lg:grid lg:grid-cols-3 mb-10`, state?.twoFactorRedirect ? tw`mt-4` : tw`mt-10`]}>
                <ContentBox title={t('password.title')} showFlashes={'account:password'}>
                    <UpdatePasswordForm />
                </ContentBox>
                <ContentBox css={tw`mt-8 sm:mt-0 sm:ml-8`} title={t('email.title')} showFlashes={'account:email'}>
                    <UpdateEmailAddressForm />
                </ContentBox>
                <ContentBox css={tw`md:ml-8 mt-8 md:mt-0`} title={t('two_factor.title')}>
                    <ConfigureTwoFactorForm />
                </ContentBox>
            </Container>
        </PageContentBlock>
    );
};
