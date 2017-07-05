<template>
  <span>
    <span v-for="item in items">
      <router-link :to="{
        name: item.id,
        params: item.params,
        query: appendStateQuery()
      }"
        :class="[$route.fullPath.includes(item.id) ? 'is-active' : '']"
        class="nav-item">{{ item.name }}</router-link>
    </span>
  </span>
</template>
<script>
import MyMixin from '../mixin.js';
export default {
  mixins: [MyMixin],
  component: 'secondary-nav',
  data() {
    return {
      items: [
        {
          name: 'Events',
          id: 'events',
          params: { 'when': 'this-month' }
        },
        {
          name: 'Venues',
          id: 'venues',
          params: {}
        }
      ]
    }
  }
}
</script>