<template>
<div>
  <schedule-add class="row is-item grid is-100"></schedule-add>
  <action-bar :type="'schedule'" :item="schedule" :key="schedule.id" v-for="schedule in schedules" class="row"></action-bar>
</div>
</template>
<script>
import scheduleAdd from '~/components/Global/AddSchedule';
import actionBar from '~/components/Global/ActionBar';

export default {
  components: {
    scheduleAdd,
    actionBar
  },
  computed: {
    schedules() {
      return this.$store.state.schedule.schedules;
    }
  }
}
</script>
