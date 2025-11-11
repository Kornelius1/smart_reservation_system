import "./bootstrap";
import { createApp } from "vue";
import Alpine from "alpinejs";
import "../css/app.css";
import "../css/component.css";

window.Alpine = Alpine;
Alpine.start();

import PesanMenu from "./PesanMenu.vue";
// import MenuManagement from "./manajemen-menu.js";
createApp(PesanMenu).mount("#pesan-menu");
// createApp(MenuManagement).mount("#manajemen-menu");


