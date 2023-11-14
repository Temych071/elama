import 'moment/dist/locale/ru';
import moment from "moment";
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import Maska from "maska";
import Calendar from "v-calendar";
import Utils from '@/utils';
import {createI18n} from "vue-i18n";

import './bootstrap';

import lang from "./lang";

const locale = document.documentElement.lang ?? 'ru';
moment.locale(locale);

const i18n = createI18n({
    locale: locale,
    fallbackLocale: 'ru',
    // legacy: false,
    messages: lang,
    pluralizationRules: {
        'ru': function(choice, choicesLength) {
            // https://kazupon.github.io/vue-i18n/guide/pluralization.html#custom-pluralization
            if (choice === 0) {
                return 0;
            }

            const teen = choice > 10 && choice < 20;
            const endsWithOne = choice % 10 === 1;

            if (choicesLength < 4) {
                return (!teen && endsWithOne) ? 1 : 2;
            }

            if (!teen && endsWithOne) {
                return 1;
            }

            if (!teen && choice % 10 >= 2 && choice % 10 <= 4) {
                return 2;
            }

            return (choicesLength < 4) ? 2 : 3;
        }
    }
});

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    progress: {
        color: '#1a75ff',
    },
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(i18n)
            .use(Utils)
            .use(Maska)
            .use(Calendar)
            .mixin({methods: {route}})
            .mount(el);
    },
});
