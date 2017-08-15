<template>
<div>
  <!-- <event-mini> -->
  <div class="content row is-item" v-for="event in events">
    <div class="event-mini">
      <div class="grid is-10">
        <schedule-add-to-master :event="event"></schedule-add-to-master>
      </div>
      <div class="grid is-90">
        <div class="title">
          <router-link :to="{ name: 'event-single', params: { id: event.id, slug: event.slug } }">
            {{ event.title }}
          </router-link>
          <router-link :to="{ name: 'action-main', params: { id: event.id } }" class="align-right">
            <icon name="ellipsis-h"></icon>
          </router-link>
        </div>
        <div class="body">
          <div>
            {{ event.venue.name }} &bull; {{ event.venue.city.name }}<span v-if="event.venue.city.states">, {{ event.venue.city.states[0].abbr }}</span>
          </div>
        </div>
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
    if (!_.isEmpty(window.laravel.user)) {
      this.$store.dispatch('fetchAllScheduledEventIds');
    }
  }
}
</script>