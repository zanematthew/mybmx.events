<template>
<div class="masthead row">
  <div class="nav is-underlined is-primary">
    <router-link v-for="item in items" :key="item.id" :to="{
      name: item.id,
      params: item.params,
    }"
    class="nav-item"
    ><icon :name="item.icon"></icon></router-link>
    <router-link :to="{ name: 'schedules' }" class="nav-item icon-avatar">
      <span class="avatar"><img :src="avatar" /></span>
    </router-link>
  </div>
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
            name: 'Collections',
            id: 'collection',
            icon: 'star-o'
          }
        ]
    }
  },
  computed: {
    ...mapGetters([
      'avatar'
    ])
  }
}
</script>
<style lang="scss">
@import "../../../sass/variables";
.masthead {
  background: #f5f5f5;
  border-bottom: 1px solid $light-gray;
  min-height: 40px;
}

.is-primary {
  width: 100%;
  .nav-item {
    padding: 15px 0 10px;
    text-align: center;
    width: 25%;
    float: left;
  }
}

.nav-item {
 &.icon-avatar {
    min-height: 45px;
    padding: 6px 0 0;
  }
}
</style>