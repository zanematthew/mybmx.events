<template>
  <div>
    <schedule-add-to-master class="grid is-20 align-center" :event="event"></schedule-add-to-master>
    <div class="grid is-80">
      <router-link v-if="event.id" :to="{ name: 'event-single', params: { id: event.id, slug: event.slug } }" class="title">{{ event.title }}</router-link>
      <router-link v-if="event.id" :to="{ name: 'action-main', params: { id: event.id, landingUrl: landingUrl } }" class="align-right">
        <icon name="ellipsis-h"></icon>
      </router-link>
      <div>
        <strong>{{ fromNow(event.start_date) }}</strong>, {{ startEndDate(event.start_date, event.end_date) }}
      </div>
      <div>
        <strong>{{ event.type_name }}</strong> &bull; <strong>{{ event.venue.name }}</strong> &bull; {{ event.venue.city.name }}<span v-if="event.venue.city.states">, {{ event.venue.city.states[0].abbr }}</span>
      </div>
    </div>
  </div>
</template>
<script>
import MyMixin from '../../mixin.js';
import ScheduleAddToMaster from '../../components/Schedule/AddToMaster';

export default {
  mixins: [MyMixin],
  props: ['event'],
  components: {
    'schedule-add-to-master': ScheduleAddToMaster
  }
}
</script>