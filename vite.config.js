import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.tsx',
            refresh: true,
        }),
        react(),
    ],
    build: {
        // generate manifest.json in outDir
        manifest: true,
        modulePreload: {
            resolveDependencies: () => [],
        },
        rollupOptions: {

            // overwrite default .html entry
            input: 'resources/js/app.tsx',
        },
    },
});
