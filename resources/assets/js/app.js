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
import Clients from './components/passport/Clients';
import AuthorizedClients from './components/passport/AuthorizedClients';
import PersonalAccessTokens from './components/passport/PersonalAccessTokens';
import PrimaryNav from './components/PrimaryNav';

import VueAnalytics from 'vue-analytics';
Vue.use(VueAnalytics, {
  id: 'UA-102119335-1',
  router,
  autoTracking: {
    pageviewTemplate: function (route) {
      return {
        page: route.path,
        title: document.title,
        location: window.location.href
      }
    }
  }
});

const app = new Vue({
  router,
  nprogress,
  data: {
    primaryNav: [
      {
        name: 'Browse',
        id: 'browse',
        params: { 'when': 'this-month' }
      },
      {
        name: 'Schedules',
        id: 'schedules'
      }
    ],
    secondaryNav: [
      {
        name: 'Events',
        id: 'events',
        params: { 'when': 'this-month' }
      },
      {
        name: 'Venues',
        id: 'venues',
        params: {}
      }
    ]
  },
  metaInfo: {
    title: '...',
    titleTemplate: '%s | My BMX Events'
  },
  components: {
    'state-select': StateSelect,
    'primary-nav': PrimaryNav,
    'secondary-nav': SecondaryNav,
    'passport-clients': Clients,
    'passport-authorized-clients': AuthorizedClients,
    'passport-personal-access-tokens': PersonalAccessTokens,
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
