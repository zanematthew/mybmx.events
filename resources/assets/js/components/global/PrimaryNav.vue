<template>
<div class="nav is-underlined is-primary">
  <router-link v-for="item in items" :key="item.id" :to="{
    name: item.id,
    params: item.params,
  }"
  class="nav-item"
  ><icon :name="item.icon"></icon></router-link>
  <router-link v-if="isLoggedIn" :to="{ name: 'schedules' }" class="nav-item icon-avatar">
    <span class="avatar"><img :src="avatar" /></span>
  </router-link>
  <router-link v-else :to="{ name: 'schedules' }" class="nav-item">
    <icon :name="'user'"></icon>
  </router-link>
</div>
</template>
<script>
import MyMixin from '~/mixin.js';
import { mapGetters } from 'vuex';

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
            name: 'collections',
            id: 'collections',
            icon: 'star-o',
          }
        ]
    }
  },
  computed: {
    ...mapGetters([
      'isLoggedIn',
      'avatar'
    ])
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