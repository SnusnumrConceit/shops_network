
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
import store from './store/index';

import Paginate from 'vuejs-paginate'
Vue.component('paginate', Paginate);

import App from './components/app.vue';

/** v-modal **/
import VModal from 'vue-js-modal'

Vue.use(VModal);

/** SwAl2**/
import Vue from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);


/** Router **/
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import { routes } from './router.js';
const router = new VueRouter({routes});

import Vuex from 'vuex';

Vue.use(Vuex);
// const files = require.context('./', true, /\.vue$/i)

// files.keys().map(key => {
//     return Vue.component(_.last(key.split('/')).split('.')[0], files(key))
// })

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    store
});
