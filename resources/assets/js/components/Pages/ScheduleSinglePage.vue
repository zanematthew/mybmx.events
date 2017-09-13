<template>
  <div v-if="items">
    <div v-if="count === 0">
      <router-link :to="{ name: 'event-list-page', params: { when: 'this-month' } }" class="grid row is-item title align-center">Add Events to this Schedule.</router-link>
    </div>
    <div v-else>
      <action-bar :type="'event'" :item="item" :key="item.id" v-for="item in items.events" class="row"></action-bar>
    </div>
  </div>
  <div v-else class="align-center row is-item grid is-100">
    <icon name="refresh" spin></icon>
  </div>
</template>
<script>
import actionBar from '~/components/Global/ActionBar';

export default {
  components: {
    actionBar
  },
  props: ['id'],
  computed: {
    items() {
      return this.$store.getters.getEventsInScheduleByScheduleId(this.id);
    },
    count() {
      return _.isUndefined(this.items.events) ? 0 : this.items.events.length;
    }
  }
}
</script>