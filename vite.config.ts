import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'node:path';
import { defineConfig, loadEnv } from 'vite';

// A função defineConfig pode receber uma função que nos dá acesso ao 'mode' (development, production, etc.)
export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const serverUrl = env.VITE_DEV_SERVER_URL || 'http://localhost:8080';

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.tsx'],
                ssr: 'resources/js/ssr.tsx',
                refresh: true,
            }),
            react(),
            tailwindcss(),
        ],
        server: {
            host: '0.0.0.0',
            port: 5173,
            origin: serverUrl,
            hmr: {
                host: new URL(serverUrl).hostname,
                path: '/hmr',
            },
        },
        esbuild: {
            jsx: 'automatic',
        },
        resolve: {
            alias: {
                'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
            },
        },
    };
});
