import '../scss/app.scss'
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import Notifications from '@kyvg/vue3-notification';
import velocity from 'velocity-animate';
import { router } from '@inertiajs/vue3'

import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import store from './Store/index.js';
import  vSelect from "vue-select";
const appName = import.meta.env.BUGTRACKLY_VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} / ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(Notifications, {velocity})
            .use(store) // Utiliser Vuex store
            .use(ZiggyVue)
            .component("v-select", vSelect)
            .mount(el);
    },
    progress: {
        color: '#1b84ff',
    },
});


router.on('before', (event) => {
    store.commit('navigation/closeMobileNav');
})
