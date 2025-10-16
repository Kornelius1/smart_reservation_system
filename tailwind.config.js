import forms from "@tailwindcss/forms";
import daisyui from "daisyui";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
        "./resources/js/**/*.js", // Pastikan path JS ada di sini
    ],

    theme: {
        extend: {
            colors: {
                "brand-background": "#dfe6da",
                "brand-text": "#414939",
                "brand-primary": "#9CAF88",
                "brand-primary-dark": "#414939",
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, require("daisyui")],

    daisyui: {
        themes: [
            {
                homey: {
                    primary: "#414939", // Warna gelap untuk tombol dan teks utama
                    secondary: "#9CAF88", // Warna terang untuk ornamen dan tombol
                    accent: "#9CAF88", // Warna ornamen
                    "base-100": "#FFFFFF", // Warna untuk card
                    neutral: "#2b3124", // Warna footer
                },

                light: {
                    primary: "#570df8",
                    "primary-content": "#ffffff",
                    secondary: "#f000b8",
                    "secondary-content": "#ffffff",
                    accent: "#37cdbe",
                    "accent-content": "#163835",
                    neutral: "#3d4451",
                    "neutral-content": "#ffffff",
                    "base-100": "#ffffff",
                    "base-200": "#F2F2F2",
                    "base-300": "#E5E6E6",
                    "base-content": "#1f2937",
                    info: "#3abff8",
                    "info-content": "#002b3d",
                    warning: "#fbbd23",
                    "warning-content": "#382800",
                    error: "#f87272",
                    "error-content": "#470000",

                    // Warna custom-mu yang menimpa warna 'success' default
                    success: "#9CAF88",
                    "success-content": "#ffffff",
                },
            },
        ],
    },
};
