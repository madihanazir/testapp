import './bootstrap';
import axios from 'axios';
import { createApp } from 'vue';
import Dashboard from './components/Dashboard.vue';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8000';


axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

createApp(Dashboard).mount('#app');
