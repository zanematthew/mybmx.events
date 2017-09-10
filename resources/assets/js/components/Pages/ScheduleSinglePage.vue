<template>
  <div v-if="items">
    <router-link :to="{ name: 'schedule-list' }" class="row is-item grid"><icon name="chevron-left"></icon></router-link>
    <div v-if="count === 0">
      <router-link :to="{ name: 'when', params: { when: 'this-month' } }" class="grid row is-item title align-center">Add Events to this Schedule.</router-link>
    </div>
    <div v-else>
      <action-bar :type="'event'" :item="item" :key="item.id" v-for="item in items" class="row"></action-bar>
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
      return this.$store.state.schedule.realSchedules[this.id];
    },
    count() {
      return _.isUndefined(this.items) ? 0 : this.items.length;
    }
  },
  mounted() {
    this.$store.dispatch('fetchScheduleEvents', this.id);
  }
}
</script>