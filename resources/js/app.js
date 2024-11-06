import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp, router, Link, Head } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import NProgress from 'nprogress'
import Toast, { POSITION } from 'vue-toastification'
import 'vue-toastification/dist/index.css'
import '@fortawesome/fontawesome-free/css/all.min.css'

const appName = import.meta.env.VITE_APP_NAME || 'CRM'

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const page = (
            await resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue'),
            )
        ).default

        return page
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })

        const toastOptions = {
            position: POSITION.TOP_RIGHT,
            timeout: 5000,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            progress: 'bar',
        }

        app.use(plugin)
            .use(ZiggyVue)
            .use(Toast, toastOptions)
            .component('Link', Link)
            .component('Head', Head)
            .mount(el)
    },
    progress: {
        color: '#4B5563',
    },
})

router.on('start', () => NProgress.start())
router.on('finish', () => NProgress.done())
