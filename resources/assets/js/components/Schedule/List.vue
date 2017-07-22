<template>
<div>
  <schedule-add :schedules="schedules.data"></schedule-add>
  <div v-for="schedule in schedules.data" class="content row is-item form">
    <div class="grid is-80">
      <schedule-make-default :initialSchedule="schedule"></schedule-make-default>
      <div class="main">
        <schedule-rename :initialSchedule="schedule"></schedule-rename>
      </div>
    </div>
    <schedule-delete :id="schedule.id" :schedules="schedules.data"></schedule-delete>
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
  data() {
    return {
      schedules: [],
    }
  },
  mounted() {
    Schedule.getScheduels(schedules => this.schedules = schedules);
  }
}
</script>