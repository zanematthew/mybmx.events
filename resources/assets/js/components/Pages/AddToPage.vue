<template>
  <div class="content">
    <div v-for="schedule in schedules" class="grid is-100 row is-item">
      <div class="title" v-on:click="addTo(schedule)">{{ schedule.name }}</div>
    </div>
    <div class="grid is-100 row is-item">
      <schedule-add></schedule-add>
    </div>
  </div>
</template>
<script>
import router from '~/router';
import ScheduleAdd from '~/components/Global/AddSchedule';

export default {
  components: {
    'schedule-add': ScheduleAdd
  },
  data() {
    return {
      id: this.$route.params.id
    }
  },
  computed: {
    schedules() {
      return this.$store.state.schedule.schedules;
    }
  },
  methods: {
    addTo(schedule) {
      this.$store.dispatch('toggleEventToSchedule', {
        id: this.id,
        scheduleId: schedule.id
      }).then(response => {
        alert(`Added!\nRedirect to: Some URL`);
      });
    }
  }
}
</script>
