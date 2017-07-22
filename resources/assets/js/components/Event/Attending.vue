<template>
  <div class="row">
    <div v-for="schedule in scheduled" class="content row is-item grid is-100">
      <div class="title">
        {{ schedule.name }}
      </div>
      <div v-for="event in schedule.events">
        <div class="title">{{ event.title }}</div>
        <schedule-add-to-master
          :event="event"
          :initially-scheduled="scheduled"
          :schedule-master-id="schedule.id"
          ></schedule-add-to-master>
      </div>
    </div>
  </div>
</template>
<script>
import Schedule from '../../models/Schedule';
import ScheduleAddToMaster from '../../components/Schedule/AddToMaster';

export default {
  components: {
    'schedule-add-to-master': ScheduleAddToMaster,
  },
  data() {
    return {
      scheduled: {},
      masterSchedule: {}
    }
  },
  mounted() {
    Schedule.getAttendingMaster(scheduled => this.scheduled = scheduled);
  }
}
</script>