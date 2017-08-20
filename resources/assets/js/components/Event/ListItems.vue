<template>
<div class="content">
  <div class="row is-item" v-for="event in events">
    <!-- Component: Event Action Bar -->
    <schedule-add-to-master class="grid is-20" :event="event"></schedule-add-to-master>
    <div class="grid is-80">
      <router-link :to="{ name: 'event-single', params: { id: event.id, slug: event.slug } }" class="title">{{ event.title }}</router-link>
      <router-link :to="{ name: 'action-main', params: { id: event.id, landingUrl: landingUrl } }" class="align-right">
        <icon name="ellipsis-h"></icon>
      </router-link>
      <div>
        {{ event.venue.name }} &bull; {{ event.venue.city.name }}<span v-if="event.venue.city.states">, {{ event.venue.city.states[0].abbr }}</span>
      </div>
    </div>
    <!-- Component: Event Action Bar -->
  </div>
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
  data() {
    return {
      landingUrl: ''
    }
  },
  mounted() {
    if (!_.isEmpty(window.laravel.user)) {
      this.$store.dispatch('fetchAllScheduledEventIds');
    }
    this.landingUrl = `${window.location.origin}${window.location.pathname}${window.location.search}`;
  },
  watch: {
    '$route' (to, from) {
      this.landingUrl = `${window.location.origin}${window.location.pathname}${window.location.search}`;
    }
  }
}
</script>