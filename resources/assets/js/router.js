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
import ActionFormPage from './components/Pages/ActionFormPage';
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
      }
    ]
  },
  {
    //
    // /action/295/schedule/
    // /action/295/schedule/share/
    // /action/295/schedule/toggle/952/
    //
    // /action/295/event/
    // /action/295/event/share/
    // /action/295/event/toggle/952/
    //
    // /action/295/venue/
    // /action/295/venue/share/
    // /action/295/venue/toggle/952/
    //
    // /action/295/schedule/edit/
    // @todo review "nested routes", we can probably remove the RouterView in lieu of
    // empty sub-route paths.
    path: '/action/',
    component: RouterView,
    name: 'action-routerview',
    redirect: { name: 'action-main' },
    props: true,
    children: [
      {
        // This works for upper case as well; https://github.com/vuejs/vue-router/pull/1215
        path: ':id(\\d+)/:type([a-z]+)',
        name: 'action-main',
        component: ActionMainPage,
        props: true,
      },
      // Can't use nested children because this has no RouterView to bind(?) to.
      {
        path: ':id(\\d+)/:type([a-z]+)/edit',
        name: 'action-edit',
        component: ActionFormPage,
        props: true
      },
      {
        path: 'share',
        name: 'share',
        component: SharePage,
        props: true
      },
      //
      // @todo This should be universal, same concept for
      // toggling an item to a collection!
      // add/:type/to/
      //
      {
        path: 'add/:type([a-z]+)/:id(\\d+)/to',
        name: 'add-to',
        component: AddToPage,
        props: true,
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