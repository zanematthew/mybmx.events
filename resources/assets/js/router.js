import VueRouter from 'vue-router';

import EventSingle from './components/EventSingle';
import EventsList from './components/EventsList';
import VenuesList from './components/VenuesList';
import VenueSingle from './components/VenueSingle';

const NotFoundComponent = { template: '<div>404</div>' };

// 2. Define routes
// Dynamic route matching: https://router.vuejs.org/en/essentials/dynamic-matching.html
// Passing props to Route Components: https://router.vuejs.org/en/essentials/passing-props.html
const routes = [
  // Optional route params, FTW.
  // https://github.com/vuejs/vue-router/issues/235
  // @todo pagination for routes?
  {
    path: '/event/:id/:slug?',
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
    name: 'venue-single',
    props: true
  },
  {
    path: '/venues/:state?',
    component: VenuesList,
    name: 'venues',
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

export default new VueRouter({
  // https://router.vuejs.org/en/essentials/history-mode.html
  mode: 'history',
  routes, // Short for routes: routes
  linkActiveClass: 'is-active',
  scrollBehavior (to, from, savedPosition) {
    return { x: 0, y: 0};
  }
});