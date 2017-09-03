<template>
<div class="content">
  <div v-if="event" class="action-bar">

    <schedule-add-to-master :event="event" class="grid is-15"></schedule-add-to-master>

    <router-link v-if="event.id" :to="{ name: 'event-single',
      params: { id: event.id, slug: event.slug, when: 'this-month' },
      query: { venue_id: event.venue.id }
      }" class="grid is-70 title-click-area">
        <div class="title">{{ event.title }} {{ event.venue.name }}</div>
        <span v-if="event.venue">
          {{ event.venue.city.name }}<span v-if="event.venue.city.states">, {{ event.venue.city.states[0].abbr }}</span>
        </span>
        {{ startEndDate(event.start_date, event.end_date) }}
    </router-link>

    <router-link v-if="event.id" :to="{ name: 'action-main', params: { id: event.id } }" class="align-right grid is-15 detail-click-area">
      <icon name="ellipsis-h"></icon>
    </router-link>
  </div>

  <div v-else-if="venue" class="action-bar">
    <div class="grid is-20 image-area" v-if="venue.image_uri">
      <img :src="venue.image_uri" itemprop="image" alt="Photo of Jane Joe">
    </div>
    <div class="grid is-65 title-click-area">
      <router-link :to="{ name: 'venue-single-events', params: { venue_id: venue.id, slug: venue.slug, when: 'this-month' } }" class="title" v-if="venue.id">{{ venue.name }}</router-link>
    </div>
    <router-link v-if="venue.id" :to="{ name: 'action-main', params: { id: venue.id } }" class="align-right grid is-15 detail-click-area">
      <icon name="ellipsis-h"></icon>
    </router-link>
  </div>

  <div v-else class="title">
    No item to take action on.
  </div>

</div>
</template>
<script>
import MyMixin from '~/mixin.js';
import ScheduleAddToMaster from '~/components/Global/AddToMasterSchedule';

export default {
  mixins: [MyMixin],
  props: {
    event: { id: '' },
    venue: { id: '' }
  },
  components: {
    'schedule-add-to-master': ScheduleAddToMaster
  }
}
</script>
<style lang="scss">
@import "../../../sass/variables";
.action-bar {
  max-height: 80px;
  min-height: 80px;
  height: 80px;
}
.title-click-area {
  height: 100%;
  padding-top: $padding;
}
.detail-click-area {
  height: 100%;
  color: #000;
  padding-top: $padding;
}
.image-area {
  padding-top: $padding;
  padding-bottom: $padding;
}
</style>