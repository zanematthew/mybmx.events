<template>
  <div>
      <router-link :to="{ name: 'collection-type', params: { item_type: 'event' } }" class="grid is-100 row is-item title">Events</router-link>
      <!-- <router-link :to="{ name: 'collection-type', params: { item_type: 'schedule' } }" class="grid is-100 row is-item title">Schedules</router-link> -->
      <router-link :to="{ name: 'collection-type', params: { item_type: 'venue' } }" class="grid is-100 row is-item title">Venues</router-link>
  </div>
</template>
