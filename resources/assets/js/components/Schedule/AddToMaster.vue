<template>
  <div class="attend-container" :class="isScheduled(event) ? 'attending' : ''" v-on:click.stop.prevent="schedule(event)">
    <icon scale="2" :name="isScheduled(event) ? 'check-circle' : 'plus-circle'"></icon>
  </div>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
  props: {
    event: {
      type: Object,
      required: true
    },
  },
  computed: {
    ...mapGetters([
      'allEventIds'
    ])
  },
  methods: {
    schedule(event) {
      this.$store.dispatch('addToMasterSchedule', event);
    },
    isScheduled(event) {
      return this.allEventIds.indexOf(event.id) != -1 ? true : false;
    }
  }
}
</script>