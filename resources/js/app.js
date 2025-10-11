import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { createApp } from 'vue';

import PesanMenu from './PesanMenu.vue'; 

createApp(PesanMenu).mount('#pesanmenu');
