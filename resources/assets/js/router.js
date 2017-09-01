import VueRouter from 'vue-router';

import EventRouterView from './components/Event/RouterView';
import EventSinglePage from './components/Event/SinglePage';
import EventListPage from './components/Event/ListPage';
import EventAttendingPage from './components/Event/AttendingPage';

import VenueRouterView from './components/Venue/RouterView';
import VenueSingle from './components/Venue/Single';
import VenueList from './components/Venue/List';

import ScheduleRouterView from './components/Schedule/RouterView';
import ScheduleList from './components/Schedule/List';

import ActionMain from './components/global/ActionMain';
import Share from './components/global/Share';
import AddTo from './components/Schedule/AddTo';

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
        path: ':id(\\d+)/:slug/:when',
        component: EventSinglePage,
        name: 'event-single',
        props: true
      },
      {
        path: ':when',
        component: EventListPage,
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
        path: ':venue_id(\\d+)/:slug/events/:when/',
        component: VenueSingle,
        name: 'venue-single-events',
        props: true,
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
        component: ScheduleList,
        meta: { requiresAuth: true },
      }
    ]
  },
  {
    path: '/attending/',
    component: EventAttendingPage,
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
      },
      {
        path: 'add-to',
        name: 'add-to',
        component: AddTo,
        props: true,
        meta: { requiresAuth: true }
      }
    ]
  },
  {
    path: '/',
    redirect: { name: 'events' }
  },
  {
    path: '*',
    component: NotFoundComponent
  }
];

export default new VueRouter({
  mode: 'history',
  routes,
  linkActiveClass: 'is-active'
});