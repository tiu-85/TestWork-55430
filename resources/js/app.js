/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import App from './components/App.vue';
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import axios from 'axios';
import {routes} from './routes';

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import VueDataTables from 'vue-data-tables'

import lang from 'element-ui/lib/locale/lang/ru-RU'
import locale from 'element-ui/lib/locale'

import UploadImage from './components/product/UploadImage.vue';

require('./bootstrap');

window.Vue = require('vue').default;

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(ElementUI)
Vue.use(VueDataTables)

locale.use(lang)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('image-upload', UploadImage);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const app = new Vue({
    el: '#app',
    router: router,
    components: { App }
});

