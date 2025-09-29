import daisyui from 'daisyui'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  plugins: [
    daisyui,
  ],
  daisyui: {
    themes: [
      {
        light: {
          "primary": "#16a34a",
          "primary-content": "#ffffff", 
          "secondary": "#059669",
          "secondary-content": "#ffffff",
          "accent": "#0d9488",
          "accent-content": "#ffffff",
          "neutral": "#374151",
          "neutral-content": "#ffffff",
          "base-100": "#ffffff",
          "base-200": "#f9fafb",
          "base-300": "#f3f4f6", 
          "base-content": "#111827",
          "info": "#3b82f6",
          "info-content": "#ffffff",
          "success": "#16a34a",
          "success-content": "#ffffff",
          "warning": "#f59e0b",
          "warning-content": "#000000",
          "error": "#dc2626",
          "error-content": "#ffffff",
        },
      },
      "dark",
    ],
  },
}