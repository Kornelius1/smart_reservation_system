import "./bootstrap";
import { createApp } from "vue";
import Alpine from "alpinejs";
import "../css/app.css";
import "../css/component.css";

import MenuManagement from "./manajemen-menu.js";
import PesanMenu from "./PesanMenu.vue";

const app = createApp({});

app.component("manajemen-menu", MenuManagement);
app.component("pesan-menu", PesanMenu);

app.mount("#app");

window.Alpine = Alpine;
Alpine.start();
