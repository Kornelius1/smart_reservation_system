import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            // -----------------------------------------------------------------
            // INI ADALAH BAGIAN YANG DIPERBARUI
            // -----------------------------------------------------------------
            input: [
                "resources/css/app.css",
                "resources/css/component.css",
                "resources/css/loginregister.css",
                "resources/css/manajemen.css",
                "resources/css/reservasi.css",

                "resources/js/app.js",
                "resources/js/manajemen-meja.js",
                "resources/js/admin.js",
                "resources/js/bootstrap.js",
                "resources/js/manajemen-laporan.js",
                "resources/js/manajemen-menu.js",
                "resources/js/manajemen-reschedule.js",
                "resources/js/manajemen-reservasi.js",
                "resources/js/manajemen-ruangan.js",
            ],
            // -----------------------------------------------------------------
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },
});
