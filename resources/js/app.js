import './bootstrap';
import axios from 'axios';
import { createApp } from 'vue';
import Dashboard from './components/Dashboard.vue';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8000';

createApp(Dashboard).mount('#app');
