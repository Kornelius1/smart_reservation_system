import forms from "@tailwindcss/forms";
import daisyui from "daisyui";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {},
  },
  plugins: [forms, daisyui],

  daisyui: {
    themes: [
      {
        homey: {
          "primary": "#414939",   // Warna gelap untuk tombol dan teks utama
          "secondary": "#9CAF88",  // Warna terang untuk ornamen dan tombol
          "accent": "#9CAF88",     // Warna ornamen
          "base-100": "#FFFFFF",  // Warna untuk card
          "neutral": "#2b3124",   // Warna footer
        },
      },
    ],
  },
} 

