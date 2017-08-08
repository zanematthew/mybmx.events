<template>
  <div class="attend-container" :class="isScheduled(event) ? 'attending' : ''" v-on:click.stop.prevent="schedule(event)">
    <icon scale="2" :name="isScheduled(event) ? 'check-circle' : 'plus-circle'"></icon>
  </div>
</template>
<script>
export default {
  props: {
    event: {
      type: Object,
      required: true
    },
  },
  methods: {
    schedule(event) {
      if (_.isEmpty(window.laravel.user)) {
        window.location.replace(`${window.location.origin}/login/`);
      }
      this.$store.dispatch('addToMasterSchedule', event);
    },
    isScheduled(event) {
      return this.$store.state.user.schedule.allEventIds.indexOf(event.id) != -1 ? true : false;
    }
  }
}
</script>