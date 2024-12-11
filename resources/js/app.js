// import '@unocss/reset/tailwind.css'
// import '../css/bugtrackly-icons.css'
// import 'virtual:uno.css'
import '../scss/app.scss'
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { router } from '@inertiajs/vue3'

import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import store from './store/index.js';
import {useStore} from 'vuex'
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(store) // Utiliser Vuex store
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});


router.on('before', (event) => {
    console.log(`About to make a visit to ${event.detail.visit.url}`)
    store.commit('navigation/closeMobileNav');
})
