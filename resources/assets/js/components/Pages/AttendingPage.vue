<template>
  <div class="content">
      <action-bar :type="'event'" :item="event" v-for="event in masterSchedule.events" :key="event.id" class="row is-item"></action-bar>
  </div>
</template>
<script>
import actionBar from '~/components/Global/ActionBar';

export default {
  components: {
    actionBar
  },
  computed: {
    masterSchedule() {
      return this.$store.state.schedule.master
    }
  }
}
</script>