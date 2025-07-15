import "./bootstrap";
import "../css/app.css";
import "ant-design-vue/dist/reset.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { ConfigProvider } from "ant-design-vue";

const appName = import.meta.env.VITE_APP_NAME || "MAIA";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({
            render: () =>
                h(
                    ConfigProvider,
                    {
                        theme: {
                            token: {
                                colorPrimary: "#db2777", // bg-pink-600 de Tailwind
                            },
                        },
                    },
                    [h(App, props)]
                ),
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
