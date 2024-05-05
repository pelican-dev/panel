import React from 'react';
import { useTranslation } from 'react-i18next';
import Can from '@/components/elements/Can';
import { ServerError } from '@/components/elements/ScreenBlock';

export interface RequireServerPermissionProps {
    permissions: string | string[];
}

const RequireServerPermission: React.FC<RequireServerPermissionProps> = ({ children, permissions }) => {
    const { t } = useTranslation('strings');

    return (
        <Can
            action={permissions}
            renderOnError={<ServerError title={t('access_denied.title')} message={t('access_denied.message')} />}
        >
            {children}
        </Can>
    );
};

export default RequireServerPermission;
