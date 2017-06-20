'use strict';
/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/

require('./bootstrap');

// Require Vue
window.Vue = require('vue');

// 0.
// Import VueRouter
// Call/use VueRouter in our app.
// https://router.vuejs.org/en/essentials/getting-started.html
import VueRouter from 'vue-router';
Vue.use(VueRouter);

// 1. Define components
import EventSingle from './components/EventSingle';
import EventsList from './components/EventsList';
// https://github.com/vue-bulma/nprogress#configuration
import NProgress from 'vue-nprogress';
import nprogress from './nprogress';
Vue.use(NProgress);

const VenueSingle = {
  props: ['id', 'slug'],
  template: '<div>Venue <strong>{{ id }}</strong> slug: <strong>{{ slug }}</strong></div>'
};

const VenuesList = {
  props: ['state'],
  template: '<div>Venue <strong>{{ state }}</strong></div>'
};

const NotFoundComponent = { template: '<div>404</div>' };

// 2. Define routes
// Dynamic route matching: https://router.vuejs.org/en/essentials/dynamic-matching.html
// Passing props to Route Components: https://router.vuejs.org/en/essentials/passing-props.html
const routes = [
  // Optional route params, FTW.
  // https://github.com/vuejs/vue-router/issues/235
  // @todo pagination for routes?
  {
    path: '/event/:id?/:slug?',
    component: EventSingle,
    name: 'event-single',
    props: true
  },
  {
    path: '/events/:when',
    component: EventsList,
    name: 'events',
    props: true
  },
  {
    path: '/venue/:id/:slug?',
    component: VenueSingle,
    props: true
  },
  {
    path: '/venues/:state?',
    component: VenuesList,
    props: true
  },

  // HTML5 History Mode
  // Part of Caveat for catching 404 pages.
  // @todo investigate headers to ensure 404 code is thrown.
  {
    path: '*',
    component: NotFoundComponent
  }
];

// 3. Create route instance
const router = new VueRouter({
  // https://router.vuejs.org/en/essentials/history-mode.html
  mode: 'history',
  routes, // Short for routes: routes
  linkActiveClass: 'is-active'
});

// 4. Create and mount root instance.
const app = new Vue({
  router,
  nprogress
}).$mount('#app');
