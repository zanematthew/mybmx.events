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
  computed: {
    allEventIds() {
      return this.$store.state.schedule.allEventIds;
    }
  },
  methods: {
    schedule(event) {
      if (this.$store.isLoggedIn) {
        this.$store.dispatch('addToMasterSchedule', event);
      } else {
        window.location.replace(`${window.location.origin}/login/`);
      }
    },
    // @todo move to getter in Vuex
    isScheduled(event) {
      return this.allEventIds.indexOf(event.id) != -1 ? true : false;
    }
  }
}
</script>
<style lang="scss">
@import "../../../sass/variables";
.attend-container {
  height: 100%;
  padding-top: $padding;
  text-align: center;
}
</style>