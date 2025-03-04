
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { LocalizationProvider } from '@mui/x-date-pickers';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs'
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name: string): Promise<any> =>
        resolvePageComponent(
            `./Pages/${name}.tsx`,
            import.meta.glob('./Pages/**/*.tsx', { eager: true })
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <LocalizationProvider dateAdapter={AdapterDayjs}>
                <App {...props} />
            </LocalizationProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});
