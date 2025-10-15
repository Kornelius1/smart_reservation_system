import './bootstrap';
import { createApp } from 'vue';
import MenuManagement from './components/MenuManagement.vue';

const app = createApp({});
app.component('manajemen-menu', MenuManagement);

app.mount('#app');