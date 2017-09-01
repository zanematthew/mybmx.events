<template>
  <div class="content">
    <div class="row is-item" v-for="event in masterSchedule.events">
      <action-bar :event="event"></action-bar>
    </div>
  </div>
</template>
<script>
import ActionBar from '~/components/global/ActionBar';

export default {
  components: {
    'action-bar': ActionBar
  },
  computed: {
    masterSchedule() {
      return this.$store.state.schedule.master
    }
  }
}
</script>