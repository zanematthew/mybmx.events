import VueRouter from 'vue-router';

import EventSingle from './components/EventSingle';
import EventsMain from './components/EventsMain';
import VenuesList from './components/VenuesList';
import VenueSingle from './components/VenueSingle';
import ScheduleMain from './components/ScheduleMain';
import ScheduleSingle from './components/ScheduleSingle';

const NotFoundComponent = { template: '<div>404</div>' };

const routes = [
  {
    path: '/browse/events/:when',
    component: EventsMain,
    name: 'events',
    props: true
  },
  {
    path: '/browse/event/:id/:slug?',
    component: EventSingle,
    name: 'event-single',
    props: true
  },
  {
    path: '/browse/venues/:state?',
    component: VenuesList,
    name: 'venues',
    props: true
  },
  {
    path: '/browse/venue/:id/:slug?',
    component: VenueSingle,
    name: 'venue-single',
    props: true
  },
  {
    path: '/schedules',
    component: ScheduleMain,
    name: 'schedules',
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
  mode: 'history',
  routes,
  linkActiveClass: 'is-active',
  scrollBehavior (to, from, savedPosition) {
    return { x: 0, y: 0};
  }
});