import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import inertia from "@inertiajs/vite";
import react from "@vitejs/plugin-react";
import { wayfinder } from "@laravel/vite-plugin-wayfinder";
import path from "path";

export default defineConfig({
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js"),
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/public.css",
                "resources/js/app.tsx",
            ],
            refresh: true,
        }),
        inertia(),
        react({
            babel: {
                plugins: ["babel-plugin-react-compiler"],
            },
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
