import { createServer } from 'node:http';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { renderToString } from 'vue/server-renderer';
import { createSSRApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const pages = import.meta.glob('./Pages/**/*.vue');
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const render = page => createInertiaApp({
    page,
    render: renderToString,
    title: title => !title ? appName : title.endsWith(' | TS Repair Service') ? title : `${title} - ${appName}`,
    resolve: name => pages[`./Pages/${name}.vue`](),
    setup: ({ App, props, plugin }) => createSSRApp({ render: () => h(App, props) })
        .use(plugin).component('Link', Link).use(ZiggyVue, page.props.ziggy),
});

// Inertia's render protocol, restricted to loopback: never expose page props publicly.
createServer(async (request, response) => {
    response.setHeader('Content-Type', 'application/json');
    try {
        if (request.url === '/health') return response.end(JSON.stringify({ status: 'OK' }));
        if (request.url === '/shutdown') { response.end(); return process.exit(0); }
        if (request.url !== '/render' || request.method !== 'POST') {
            response.statusCode = 404;
            return response.end('{}');
        }
        let body = '';
        for await (const chunk of request) {
            body += chunk;
            if (body.length > 2_000_000) throw new Error('SSR payload too large');
        }
        response.end(JSON.stringify(await render(JSON.parse(body))));
    } catch (error) {
        console.error('SSR render failed:', error.message);
        response.statusCode = 500;
        response.end('{}');
    }
}).listen(Number(process.env.SSR_PORT || 13714), '127.0.0.1');
