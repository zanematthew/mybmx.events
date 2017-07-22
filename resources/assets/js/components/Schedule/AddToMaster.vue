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
import Schedule from '../../models/Schedule';

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
    }
  },
  computed: {
    scheduled() {
      return this.initiallyScheduled;
    }
  },
  methods: {
    schedule(eventId) {
      Schedule.toggleAttendToMaster( response => {
        if (response.toggled.attached.length == 1){
          this.scheduled.push(eventId);
        } else if (response.toggled.detached.length == 1){
          var i = this.scheduled.indexOf(eventId);
          if ( i != -1) {
            this.scheduled.splice(i, 1);
          }
        }
      }, eventId);
    },
    isScheduled(id) {
      return this.scheduled.indexOf(id) != -1 ? true : false;
    }
  }
}
</script>