import http from '@/api/http';
import i18next from 'i18next';
function getLanguage(): Promise<string> {
    return new Promise((resolve, reject) => {
        if (location.pathname.includes('/auth/')) return resolve(navigator.language.replace(/-.*/, ''));
        http.get('/api/client/account/language')
            .then(({ data }) => resolve((data || 'en')))
            .catch(reject);
    });
}
function setLanguageInI18n(i18n: typeof i18next): Promise<string> {
    return new Promise(async (resolve, reject) => {
        const lng = await getLanguage();
        i18n.changeLanguage(lng, resolve);
    });
}
export { getLanguage, setLanguageInI18n }
