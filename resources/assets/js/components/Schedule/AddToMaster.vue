<template>
<!--
  This button will; On click add the event to the "master" schedule.
  At a later time we will introduce the "..." icon which will,
  open a drop down with the following functionality:
  - Add to Event to most recently used Schedule
  - Add Event to Schedule...
-->
  <div class="attend-container" :class="isScheduled(event.id) ? 'attending' : ''" v-on:click="schedule(event.id)">
    <icon scale="2" :name="isScheduled(event.id) ? 'check-circle' : 'plus-circle'"></icon>
  </div>
</template>
<script>
export default {
  props: {
    // Current event
    event: {
      type: Object,
      required: true
    },

    // Array of event IDs that are already scheduled
    initiallyScheduled: {
      type: Array,
      required: true
    },

    scheduleMasterId: {
      type: Number,
      // required: true,
    }
  },
  computed: {
    scheduled() {
      return this.initiallyScheduled;
    },
    masterId() {
      return this.masterScheduleId
    }
  },
  methods: {
    schedule(eventId) {
      axios.post(`/api/schedules/${eventId}/attend/${this.scheduleMasterId}/`).then(response => {
        if (response.data.toggled.attached.length == 1){
          this.scheduled.push(eventId);
        } else if (response.data.toggled.detached.length == 1){
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