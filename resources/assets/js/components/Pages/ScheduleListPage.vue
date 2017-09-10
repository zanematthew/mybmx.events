<template>
<div>
  <schedule-add class="row is-item grid is-100"></schedule-add>
  <div v-for="schedule in schedules" class="content row is-item form">
    <!-- <schedule-rename :schedule="schedule" ref="renameRef" class="grid is-85"></schedule-rename>     -->

    <router-link :to="{
    name: 'schedule-single',
    params: { id: schedule.id, slug: schedule.slug, name: schedule.name }
    }" class="grid is-85 title">{{ schedule.name }}</router-link>

    <dropdown :schedule="schedule" v-on:doRename="triggerRename" class="grid is-15"></dropdown>
  </div>
</div>
</template>
<script>
import scheduleAdd from '~/components/Global/AddSchedule';
import scheduleMakeDefault from '~/components/Global/SetDefaultSchedule';
import scheduleRename from '~/components/Global/RenameSchedule';
import dropdown from '~/components/Global/Dropdown';

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
