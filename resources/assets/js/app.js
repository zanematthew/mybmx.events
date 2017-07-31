'use strict';

require('./bootstrap');

window.Vue = require('vue');

/**
 * Various Vue Plugins.
 *
 * VueRouter -- SPA, https://router.vuejs.org/en/essentials/getting-started.html
 *   Note: Events, Venues, Schedule routes are handled via VueRouter.
 *   Auth, and Passport routes are handled via Laravel.
 * Vue Meta -- Manages page meta. https://github.com/declandewet/vue-meta
 * NProgress -- Progress bar at the top of the page. https://github.com/vue-bulma/nprogress
 *   Note: https://github.com/vue-bulma/nprogress/issues/13#issuecomment-312778499
 * Vue Awesome -- Font awesome as a component: https://github.com/vue-bulma/nprogress
 *   Note: Currently importing all icons for now, and registered globally.
 * VueAnalytics -- Google Analytics integration. https://github.com/MatteoGabriele/vue-analytics
 */
import VueRouter from 'vue-router';
import router from './router';
Vue.use(VueRouter);

import Meta from 'vue-meta';
Vue.use(Meta);

import NProgress from 'vue-nprogress';
import nprogress from './nprogress';
Vue.use(NProgress, {
  http: false,
  router: false
});

import 'vue-awesome/icons';
import Icon from 'vue-awesome/components/Icon';
Vue.component('icon', Icon);

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

import StateSelect from './components/StateSelect';
import SecondaryNav from './components/global/SecondaryNav';
Vue.component('secondary-nav', SecondaryNav);

/**
 * Laravel Passport -- API/JWT https://laravel.com/docs/5.4/passport
 */
import PrimaryNav from './components/PrimaryNav';
import Clients from './components/passport/Clients';
import AuthorizedClients from './components/passport/AuthorizedClients';
import PersonalAccessTokens from './components/passport/PersonalAccessTokens';

import store from './store';

const app = new Vue({
  router,
  nprogress,
  store,
  data: {
    user: window.laravel.user
  },
  metaInfo: {
    title: '...',
    titleTemplate: '%s | My BMX Events'
  },
  components: {
    'state-select': StateSelect,
    'primary-nav': PrimaryNav,
    'passport-clients': Clients,
    'passport-authorized-clients': AuthorizedClients,
    'passport-personal-access-tokens': PersonalAccessTokens
  },
  created: function () {
    // https://github.com/vue-bulma/nprogress/issues/13#issuecomment-312778499
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
