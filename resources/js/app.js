/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "vue-multiselect/dist/vue-multiselect.css";
import { createApp } from 'vue';
import Modal from "vue-bs-modal";
import App from "./components/App.vue";
import router from './router';
import request from './mixins/request';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp(App)
    .use(router)
    .use(Modal)
    .mixin(request);

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
