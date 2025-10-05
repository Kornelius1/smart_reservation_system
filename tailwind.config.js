import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                'brand-background': '#dfe6da',
                'brand-text': '#414939',
                'brand-primary': '#9CAF88',
                'brand-primary-dark': '#414939',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        require('daisyui'),
    ],

    // Konfigurasi DaisyUI dengan tema light yang sudah di-custom
    daisyui: {
        themes: [
            {
                light: {
                    "primary": "#570df8",
                    "primary-content": "#ffffff",
                    "secondary": "#f000b8",
                    "secondary-content": "#ffffff",
                    "accent": "#37cdbe",
                    "accent-content": "#163835",
                    "neutral": "#3d4451",
                    "neutral-content": "#ffffff",
                    "base-100": "#ffffff",
                    "base-200": "#F2F2F2",
                    "base-300": "#E5E6E6",
                    "base-content": "#1f2937",
                    "info": "#3abff8",
                    "info-content": "#002b3d",
                    "warning": "#fbbd23",
                    "warning-content": "#382800",
                    "error": "#f87272",
                    "error-content": "#470000",
                    
                    // Warna custom-mu yang menimpa warna 'success' default
                    "success": "#9CAF88",
                    "success-content": "#ffffff",
                },
            },
        ],
    },
};