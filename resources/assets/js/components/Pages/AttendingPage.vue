<template>
  <div class="content">
    <div class="row is-item" v-for="event in masterSchedule.events">
      <action-bar :item="event"></action-bar>
    </div>
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