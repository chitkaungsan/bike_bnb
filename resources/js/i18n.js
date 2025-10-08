// resources/js/i18n.js

import { createI18n } from 'vue-i18n';
import en from './locales/en.js';
import th from './locales/th.js';

function getInitialLocale() {
    const savedLocale = localStorage.getItem('locale');
    if (savedLocale) {
        return savedLocale;
    }
    const browserLang = navigator.language.split('-')[0];
    return ['en', 'th'].includes(browserLang) ? browserLang : 'en';
}

const i18n = createI18n({
    legacy: false,
    locale: getInitialLocale(),
    fallbackLocale: 'en',
    messages: {
        en, // This line loads the English translations
        th,
    },
});

export default i18n;