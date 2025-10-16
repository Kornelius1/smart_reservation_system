import "./bootstrap";
import { createApp } from "vue";
import Alpine from "alpinejs";

// Impor semua komponen Vue Anda di sini
import MenuManagement from "./manajemen-menu.js";
import PesanMenu from "./PesanMenu.vue";

// Buat satu aplikasi Vue
const app = createApp({});

// Daftarkan semua komponen Anda di sini
app.component("manajemen-menu", MenuManagement);
app.component("pesan-menu", PesanMenu); // Nama komponen diubah menjadi kebab-case

// Mount aplikasi utama ke elemen root, biasanya #app
app.mount("#app");

// Inisialisasi Alpine.js (tetap sama)
window.Alpine = Alpine;
Alpine.start();
