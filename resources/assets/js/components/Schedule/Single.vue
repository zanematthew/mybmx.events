<template>
  <div>
    <div v-for="item in items.events" class="row is-item">
      <router-link :to="{ name: 'event-single', params: { id: item.id, slug: item.slug } }">{{ item.title }}</router-link>
      <schedule-add-to
          :event="item"
          :initially-scheduled="scheduled"
          :defaultSchedules="schedule"
          ></schedule-add-to>
    </div>
  </div>
</template>
<script>
import ScheduleAddTo from '../../components/Schedule/AddTo';

export default {
  components: {
    'schedule-add-to': ScheduleAddTo
  },
  data() {
    return {
      items: {},
      schedule: [this.$route.params.schedule],
      currentScheduleId: [this.$route.params.id]
    }
  },
  mounted() {
    axios.get(`/api/schedules/${this.currentScheduleId}`).then(response => {
      this.items = response.data;
      var scheduledIds = [];
      _.forEach(this.items.events, function (value, key) {
        scheduledIds.push(value.id);
      });
      this.scheduled = scheduledIds;
    });
  }
}
</script>