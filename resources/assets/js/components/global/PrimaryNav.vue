<template>
<div class="nav is-underlined is-primary">
  <router-link v-for="item in items" :key="item.id" :to="{
    name: item.id,
    params: item.params,
  }"
  class="nav-item"
  ><icon :name="item.icon"></icon></router-link>
</div>
</template>
<script>
import MyMixin from '~/mixin.js';

export default {
  mixins: [MyMixin],
  data() {
    return {
        items: [
          {
            name: 'Events',
            id: 'events',
            icon: 'calendar',
            params: { 'when': 'this-month' }
          },
          {
            name: 'Venues',
            id: 'venues',
            icon: 'map-o',
          },
          {
            name: 'Attending',
            id: 'attending',
            icon: 'calendar-check-o',
          },
          {
            name: 'Your Items',
            id: 'schedules',
            icon: 'user',
          }
        ]
    }
  }
}
</script>
<style lang="scss">
.is-primary {
  display: table;
  width: 100%;
  table-layout: fixed;
  .nav-item {
    display: table-cell;
    text-align: center;
  }
}
</style>