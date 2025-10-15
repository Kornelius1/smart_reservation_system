import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "tailwindcss";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/manajemen-meja.css",
                "resources/js/app.js",
                "resources/js/bootstrap.js",
                "resources/js/manajemen-meja.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
    ],
    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },
});
