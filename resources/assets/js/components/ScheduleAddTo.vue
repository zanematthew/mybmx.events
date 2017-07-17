<template>
  <div class="attend-container" :class="isScheduled(event.id) ? 'attending' : ''" v-on:click="schedule(event.id)">
    <icon scale="2" :name="isScheduled(event.id) ? 'check-circle' : 'plus-circle'"></icon>
  </div>
</template>
<script>
export default {
  props: {
    // Current event
    event: Object,
    // Array of event IDs that are already scheduled
    initiallyScheduled: Array,
    // Array of default schedule IDs (these are the schedules
    // that this event will be added to.)
    defaultSchedules: Array
  },
  computed: {
    scheduled() {
      return this.initiallyScheduled;
    }
  },
  methods: {
    schedule(eventId) {
      var scheduleIds = [];
      if (this.defaultSchedules) {
        _.forEach(this.defaultSchedules, function (value, key){
          scheduleIds.push(value.id);
        });
      }

      // If defaultSchedules
      //    schedule id(s) = default
      // Endif
      //
      // If no defaultSchedules
      //    Get schedules
      //    Select schedule
      //    schedule id(s) = selected schedule
      // Endif
      //
      // If no schedules
      //    make a schedule
      //    set it as a default
      //    schedule id = newly created schedule
      // Endif
      //

      // Add event to schedule
      var scheduleIdsList = scheduleIds.join(',');
      axios.post(`/api/schedules/${scheduleIdsList}/attend/${eventId}/`).then(response => {
        if (response.data.toggled.attached.length == 1){
          this.scheduled.push(eventId);
        } else if (response.data.toggled.detached.length == 1){
          // Remove event from ALL schedules!
          var i = this.scheduled.indexOf(eventId);
          if ( i != -1) {
            this.scheduled.splice(i, 1);
          }
        }
      }).catch(error => {
        console.log(error);
      });
    },
    isScheduled(id) {
      return this.scheduled.indexOf(id) != -1 ? true : false;
    }
  }
}
</script>