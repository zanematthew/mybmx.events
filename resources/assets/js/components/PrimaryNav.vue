<template>
<div class="nav is-underlined is-primary">
  <span v-for="item in items">
    <router-link :to="{
      name: item.id,
      params: item.params,
    }"
    class="nav-item"
    >{{ item.name }}</router-link>
  </span>
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
            params: { 'when': 'this-month' }
          },
          {
            name: 'Venues',
            id: 'venues'
          },
          {
            name: 'Attending',
            id: 'attending'
          },
          {
            name: 'Your Items',
            id: 'schedules'
          }
        ]
    }
  }
}
</script>