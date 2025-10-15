import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
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
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

<<<<<<< HEAD
    plugins: [forms, require("daisyui")],

    daisyui: {
        themes: ["light"],
    },
};
=======
    plugins: [
        forms,
        require('daisyui'),
    ],
};
>>>>>>> ManajemenMeja1
