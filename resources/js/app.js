import './bootstrap';


import '@css/app.css';
import '@scss/app.scss';
import { createApp } from 'vue';

import App from './App.vue';
import routes from './routes';
import { createPinia } from 'pinia'

const app = createApp(App);
const pinia = createPinia()

app.use(pinia)
app.use(routes);
app.mount('#app');