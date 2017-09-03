<template>
<div>
  <div class="row is-item grid is-100">
    <schedule-add></schedule-add>
  </div>
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
import scheduleAdd from '~/components/global/AddSchedule';
import scheduleMakeDefault from '~/components/global/SetDefaultSchedule';
import scheduleRename from '~/components/global/RenameSchedule';
import dropdown from '~/components/global/Dropdown';

export default {
  components: {
    scheduleAdd,
    scheduleMakeDefault,
    scheduleRename,
    dropdown
  },
  computed: {
    schedules() {
      return this.$store.state.schedule.schedules;
    }
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
