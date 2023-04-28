/**
 * Console Vue App
 *
 * @author Seth Phat <me@sethphat.com> of ShipSaaS 2023
 */

import { createApp } from 'vue';
import App from './App.vue';
import { router } from './router';
import Notifications from '@kyvg/vue3-notification';

const app = createApp(App);

app.use(router);
app.use(Notifications);
app.mount('#app');
