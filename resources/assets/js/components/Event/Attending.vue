<template>
  <div>
    <div v-for="schedule in scheduled">
      <list-items :events="schedule.events"></list-items>
    </div>
  </div>
</template>
<script>
import Schedule from '../../models/Schedule';
import ListItems from '../../components/Event/ListItems';

export default {
  components: {
    'list-items': ListItems
  },
  data() {
    return {
      scheduled: {}
    }
  },
  mounted() {
    Schedule.getAttendingMaster(scheduled => this.scheduled = scheduled);
  }
}
</script>