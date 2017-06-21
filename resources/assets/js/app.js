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

// https://github.com/Justineo/vue-awesome
// Import all icons for now.
import 'vue-awesome/icons';

// Register globally
import Icon from 'vue-awesome/components/Icon';
Vue.component('icon', Icon);

const app = new Vue({
  router,
  nprogress
}).$mount('#app');
