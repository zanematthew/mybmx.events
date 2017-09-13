'use strict';

import VueRouter from 'vue-router';
import RouterView from './components/Pages/RouterView';
import EventSinglePage from './components/Pages/EventSinglePage';
import EventListPage from './components/Pages/EventListPage';
import VenueSinglePage from './components/Pages/VenueSinglePage';
import VenueListPage from './components/Pages/VenueListPage';
import ScheduleListPage from './components/Pages/ScheduleListPage';
import ScheduleSinglePage from './components/Pages/ScheduleSinglePage';
import ActionMainPage from './components/Pages/ActionMainPage';
import SharePage from './components/Pages/SharePage';
import AddToPage from '~/components/Pages/AddToPage';
import CollectionListPage from '~/components/Pages/CollectionListPage';
import CollectionSinglePage from '~/components/Pages/CollectionSinglePage';

const NotFoundComponent = { template: '<div>404</div>' };

const routes = [
  {
    path: '/browse/events',
    redirect: { name: 'event-list-page', params: { when: 'this-month' } },
    component: RouterView,
    name: 'events',
    props: true,
    children: [
      {
        path: ':id(\\d+)/:slug/:when',
        component: EventSinglePage,
        name: 'event-single-page',
        props: true
      },
      {
        path: ':when',
        component: EventListPage,
        name: 'event-list-page',
        props: true
      }
    ]
  },
  {
    path: '/browse/venues/',
    redirect: { name: 'venue-list-page' },
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
        name: 'venue-list-page',
        component: VenueListPage,
        props: true
      }
    ]
  },
  {
    path: '/schedules',
    name: 'schedules',
    component: RouterView,
    redirect: { name: 'schedule-list-page' },
    props: true,
    children: [
      {
        path: 'all',
        name: 'schedule-list-page',
        component: ScheduleListPage,
        props: true
      },
      {
        path: ':id(\\d+)/:slug?',
        name: 'schedule-single-page',
        component: ScheduleSinglePage,
        props: true
      }
    ]
  },
  {
    path: '/collections/',
    component: RouterView,
    name: 'collection',
    redirect: { name: 'collection-list-page' },
    props: true,
    children: [
      {
        path: 'all',
        name: 'collection-list-page',
        component: CollectionListPage,
        props: true,
      },
      {
        path: ':item_type',
        component: CollectionSinglePage,
        name: 'collection-type',
        props: true,
        // meta: { requiresAuth: true }
      }
    ]
  },
  {
    path: '/action/',
    component: RouterView,
    name: 'action-routerview',
    redirect: { name: 'action-main' },
    props: true,
    children: [
      {
        path: ':id(\\d+)',
        name: 'action-main',
        component: ActionMainPage,
        props: true,
      },
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
        // meta: { requiresAuth: true }
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