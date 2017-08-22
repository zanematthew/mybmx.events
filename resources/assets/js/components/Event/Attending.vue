<template>
  <div class="content">
    <div class="row is-item" v-for="event in scheduled.events">
      <action-bar :event="event"></action-bar>
    </div>
  </div>
</template>
<script>
import ActionBar from '../../components/Event/ActionBar';

export default {
  components: {
    'action-bar': ActionBar
  },
  computed: {
    scheduled() {
      return this.$store.state.user.schedule.master;
    }
  },
  mounted() {
    this.$store.dispatch('fetchAllScheduledEvents');
  }
}
</script>