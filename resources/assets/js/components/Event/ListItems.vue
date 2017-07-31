<template>
<div>
  <!-- <event-mini> -->
  <div class="content row is-item" v-for="event in events">
    <div class="event-mini">
      <div class="grid is-80">
        <div class="title">
          <router-link :to="{ name: 'event-single', params: { id: event.id, slug: event.slug } }">
            {{ event.title }}
          </router-link>
        </div>
        <div>
          <strong>{{ fromNow(event.start_date) }}</strong>, {{ startEndDate(event.start_date, event.end_date) }}
        </div>
        <div class="body">
          <div>
            <strong>{{ event.type_name }}</strong> &bull; <strong>{{ event.venue.name }}</strong> &bull; {{ event.venue.city.name }}<span v-if="event.venue.city.states">, {{ event.venue.city.states[0].abbr }}</span>
          </div>
        </div>
      </div>
      <div class="grid is-20 align-right">
        <schedule-add-to-master :event="event"></schedule-add-to-master>
      </div>
    </div>
  </div>
  <!-- <event-mini> -->
</div>
</template>
<script>
import Schedule from '../../models/Schedule';
import MyMixin from '../../mixin.js';
import ScheduleAddToMaster from '../../components/Schedule/AddToMaster';

export default {
  mixins: [MyMixin],
  components: {
    'schedule-add-to-master': ScheduleAddToMaster
  },
  props: ['events'],
  mounted() {
    this.$store.dispatch('fetchAllScheduledEventIds');
  }
}
</script>