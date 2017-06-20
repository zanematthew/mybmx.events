'use strict';
/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/

require('./bootstrap');

// Require Vue
window.Vue = require('vue');

// Import VueRouter
// Call/use VueRouter in our app.
// https://router.vuejs.org/en/essentials/getting-started.html
import VueRouter from 'vue-router';
import router from './router';
Vue.use(VueRouter);

// https://github.com/vue-bulma/nprogress#configuration
import NProgress from 'vue-nprogress';
import nprogress from './nprogress';
Vue.use(NProgress);

const app = new Vue({
  router,
  nprogress
}).$mount('#app');
