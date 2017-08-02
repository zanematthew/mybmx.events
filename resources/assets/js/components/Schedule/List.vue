<template>
<div>
  <schedule-add></schedule-add>
  <div v-for="schedule in schedules" class="content row is-item form">
    <div class="grid is-80">
      <schedule-make-default :schedule="schedule"></schedule-make-default>
      <schedule-rename :schedule="schedule" ref="renameRef"></schedule-rename>
    </div>
    <div class="grid is-20">
      <dropdown :schedule="schedule" v-on:doRename="triggerRename"></dropdown>
    </div>
  </div>
</div>
</template>
<script>
import Schedule from '../../models/Schedule';
import ScheduleAdd from '../../components/Schedule/Add';
import ScheduleMakeDefault from '../../components/Schedule/MakeDefault';
import ScheduleRename from '../../components/Schedule/Rename';
import Dropdown from '../../components/Dropdown';

export default {
  components: {
    'schedule-add': ScheduleAdd,
    'schedule-make-default': ScheduleMakeDefault,
    'schedule-rename': ScheduleRename,
    'dropdown': Dropdown
  },
  computed: {
    schedules () {
      return this.$store.state.schedule.schedules;
    }
  },
  mounted() {
    this.$store.dispatch('fetchAllSchedules');
  },
  methods: {
    triggerRename(schedule) {
      for (let [index, value] of this.$refs['renameRef'].entries()) {
        if (value.schedule.id == schedule.id) {
          this.$refs['renameRef'][index].edit(schedule);
          break;
        }
      }
    }
  }
}
</script>
