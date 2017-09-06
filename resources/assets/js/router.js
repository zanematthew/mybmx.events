'use strict';

import VueRouter from 'vue-router';
import RouterView from './components/Pages/RouterView';
import EventSinglePage from './components/Pages/EventSinglePage';
import EventListPage from './components/Pages/EventListPage';
import AttendingPage from './components/Pages/AttendingPage';
import VenueSinglePage from './components/Pages/VenueSinglePage';
import VenueListPage from './components/Pages/VenueListPage';
import ScheduleListPage from './components/Pages/ScheduleListPage';
import ActionMainPage from './components/Pages/ActionMainPage';
import SharePage from './components/Pages/SharePage';
import AddToPage from '~/components/Pages/AddToPage';
import CollectionListPage from '~/components/Pages/CollectionListPage';
import CollectionSinglePage from '~/components/Pages/CollectionSinglePage';

const NotFoundComponent = { template: '<div>404</div>' };

const routes = [
  {
    path: '/browse/events',
    redirect: { name: 'when', params: { when: 'this-month' } },
    component: RouterView,
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
    component: RouterView,
    name: 'venues',
    props: true,
    children: [
      {
        path: ':venue_id(\\d+)/:slug/events/:when/',
        component: VenueSinglePage,
        name: 'venue-single-events',
        props: true,
      },
      {
        path: ':state?',
        name: 'state-list',
        component: VenueListPage,
        props: true
      }
    ]
  },
  {
    path: '/items',
    redirect: { name: 'your-schedules'},
    component: RouterView,
    name: 'schedules',
    props: true,
    meta: { requiresAuth: true },
    children: [
      {
        path: 'schedules',
        name: 'your-schedules',
        component: ScheduleListPage,
        meta: { requiresAuth: true },
      }
    ]
  },
  {
    path: '/collections/',
    component: CollectionListPage,
    name: 'collections',
    props: true,
    meta: { requiresAuth: true },
    children: [
      {
        path: ':item_type',
        component: CollectionSinglePage,
        name: 'collection-type',
        props: true
      }
    ]
  },
  {
    path: '/attending/',
    component: AttendingPage,
    name: 'attending',
    props: true,
    meta: { requiresAuth: true }
  },
  {
    path: '/action/:id(\\d+)/',
    name: 'action-main',
    component: ActionMainPage,
    props: true,
    children: [
      {
        path: 'share',
        name: 'share',
        component: SharePage,
        props: true
      },
      {
        path: 'add-to',
        name: 'add-to',
        component: AddToPage,
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