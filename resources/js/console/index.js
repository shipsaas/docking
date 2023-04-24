/**
 * Console Vue App
 *
 * @author Seth Phat <me@sethphat.com> of ShipSaaS 2023
 */

import { createApp } from 'vue';
import App from './App.vue';
import { router } from './router';

const app = createApp(App);

app.use(router);
app.mount('#app');
