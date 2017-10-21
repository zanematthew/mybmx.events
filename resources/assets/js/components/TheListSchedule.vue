<template>
<div>
  <schedule-button-add class="row is-item grid is-100"></schedule-button-add>
  <action-bar :type="'schedule'" :item="schedule" :key="schedule.id" v-for="schedule in schedules" class="row"></action-bar>
</div>
</template>
<script>
import scheduleButtonAdd from '~/components/ScheduleButtonAdd';
import actionBar from '~/components/ActionBar';

export default {
  components: {
    scheduleButtonAdd,
    actionBar
  },
  computed: {
    schedules() {
      return this.$store.state.schedule.schedules;
    }
  },
  metaInfo() {
    return {
      titleTemplate: 'Schedules | My BMX Events'
    }
  },
}
</script>
