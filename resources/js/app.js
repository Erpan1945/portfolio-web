import './bootstrap';
import '../css/app.css'; // Pastikan CSS Tailwind di-import

import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router'; // <--- 1. Kita panggil file router yang sudah dibuat

const app = createApp(App);

app.use(router); // <--- 2. INI KUNCI UTAMANYA! Kita suruh Vue pakai router ini.

app.mount('#app');