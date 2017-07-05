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

import Meta from 'vue-meta';
Vue.use(Meta);

// https://github.com/vue-bulma/nprogress#configuration
import NProgress from 'vue-nprogress';
import nprogress from './nprogress';
Vue.use(NProgress, {
  http: false,
  router: false
});

// https://github.com/Justineo/vue-awesome
// Import all icons for now.
import 'vue-awesome/icons';

// Register globally
import Icon from 'vue-awesome/components/Icon';
Vue.component('icon', Icon);

import StateSelect from './components/StateSelect';

import SecondaryNav from './components/SecondaryNav';

const app = new Vue({
  router,
  nprogress,
  metaInfo: {
    title: '...',
    titleTemplate: '%s | My BMX Events'
  },
  components: {
    'state-select': StateSelect,
    'secondary-nav': SecondaryNav
  },
  created: function () {
    axios.interceptors.request.use(function (config) {
        nprogress.start();
        return config;
    }, function (error) {
        return Promise.reject(error);
    });
    axios.interceptors.response.use(function (response) {
        nprogress.done();
        return response;
    }, function (error) {
        return Promise.reject(error);
    });
  }
}).$mount('#app');
