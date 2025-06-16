// vite.config.js - Simplified for CDN Tailwind

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Hanya JS, CSS pakai CDN
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    server: {
        // Optional: Custom port kalau 5173 bentrok
        // port: 5174,
        
        // Hot reload untuk blade templates
        watch: {
            include: ['resources/views/**/*.blade.php']
        }
    }
});