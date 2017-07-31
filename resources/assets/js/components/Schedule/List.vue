<template>
<div>
  <schedule-add></schedule-add>
  <div v-for="schedule in schedules" class="content row is-item form">
    <div class="grid is-80">
      <schedule-make-default :schedule="schedule"></schedule-make-default>
      <div class="main">
        <schedule-rename :schedule="schedule"></schedule-rename>
      </div>
    </div>
    <schedule-delete :schedule="schedule"></schedule-delete>
  </div>
</div>
</template>
<script>
import Schedule from '../../models/Schedule';
import ScheduleAdd from '../../components/Schedule/Add';
import ScheduleDelete from '../../components/Schedule/Delete';
import ScheduleMakeDefault from '../../components/Schedule/MakeDefault';
import ScheduleRename from '../../components/Schedule/Rename';

export default {
  components: {
    'schedule-add': ScheduleAdd,
    'schedule-delete': ScheduleDelete,
    'schedule-make-default': ScheduleMakeDefault,
    'schedule-rename': ScheduleRename
  },
  computed: {
    schedules () {
      return this.$store.state.schedule.schedules;
    }
  },
  mounted() {
    this.$store.dispatch('fetchAllSchedules');
  }
}
</script>