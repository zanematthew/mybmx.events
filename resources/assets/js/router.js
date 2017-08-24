import VueRouter from 'vue-router';

import EventRouterView from './components/Event/RouterView';
import EventSingle from './components/Event/Single';
import EventList from './components/Event/List';
import EventAttending from './components/Event/Attending';

import VenueRouterView from './components/Venue/RouterView';
import VenueSingle from './components/Venue/Single';
import VenueList from './components/Venue/List';

import ScheduleRouterView from './components/Schedule/RouterView';
import ScheduleList from './components/Schedule/List';

import ActionMain from './components/ActionMain';
import Share from './components/Share';
import AddTo from './components/AddTo';

const NotFoundComponent = { template: '<div>404</div>' };

const routes = [
  {
    path: '/browse/events',
    redirect: { name: 'when', params: { when: 'this-month' } },
    component: EventRouterView,
    name: 'events',
    props: true,
    children: [
      {
        path: ':id(\\d+)/:slug?',
        component: EventSingle,
        name: 'event-single',
        props: true
      },
      {
        path: ':when',
        component: EventList,
        name: 'when',
        props: true
      }
    ]
  },
  {
    path: '/browse/venues/',
    redirect: { name: 'state-list' },
    component: VenueRouterView,
    name: 'venues',
    props: true,
    children: [
      {
        path: ':id(\\d+)/:slug?',
        component: VenueSingle,
        name: 'venue-single',
        props: true
      },
      {
        path: ':state?',
        name: 'state-list',
        component: VenueList,
        props: true
      }
    ]
  },
  {
    path: '/items',
    redirect: { name: 'your-schedules'},
    component: ScheduleRouterView,
    name: 'schedules',
    props: true,
    meta: { requiresAuth: true },
    children: [
      {
        path: 'schedules',
        name: 'your-schedules',
        component: ScheduleList
      }
    ]
  },
  {
    path: '/attending/',
    component: EventAttending,
    name: 'attending',
    props: true,
    meta: { requiresAuth: true }
  },
  {
    path: '/action/:id(\\d+)/',
    name: 'action-main',
    component: ActionMain,
    props: true,
    children: [
      {
        path: 'share',
        name: 'share',
        component: Share,
        props: true
      }
    ]
  },
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