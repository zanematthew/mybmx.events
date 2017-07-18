<template>
  <div>
    <schedule-add :schedules="schedules.data"></schedule-add>
    <div v-for="schedule in schedules.data" class="content row is-item form">
      <div class="grid is-80">
        <router-link :to="{
        name: 'schedule-single',
        params: {
          id: schedule.id,
          slug: schedule.slug
        }
      }">{{ schedule.name }}</router-link>
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
import ScheduleAdd from '../components/ScheduleAdd';
import ScheduleDelete from '../components/ScheduleDelete';
import ScheduleMakeDefault from '../components/ScheduleMakeDefault';
import ScheduleRename from '../components/ScheduleRename';

export default {
  components: {
    'schedule-add': ScheduleAdd,
    'schedule-delete': ScheduleDelete,
    'schedule-make-default': ScheduleMakeDefault,
    'schedule-rename': ScheduleRename
  },
  data() {
    return {
      schedules: []
    }
  },
  mounted() {
    this.getScheduels();
  },
  methods: {
    getScheduels() {
      axios.get('/api/schedules/').then(response => {
        this.schedules = response.data;
      }).catch(response => {
        console.log(error);
      });
    }
  }
}
</script>
