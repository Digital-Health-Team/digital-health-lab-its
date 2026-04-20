import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import inertia from '@inertiajs/vite';
import react from '@vitejs/plugin-react';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',      
                'resources/js/app.js',        
                'resources/css/public.css',   
                'resources/js/app.tsx',       
            ],
            refresh: true,
        }),
        inertia(),
        react(),
        tailwindcss(),
        wayfinder(),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
