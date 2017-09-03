<template>
<div class="content">
  <div v-if="event">
    <schedule-add-to-master class="grid is-20 align-center" :event="event"></schedule-add-to-master>
    <div class="grid is-80">

      <router-link v-if="event.id" :to="{ name: 'event-single',
      params: { id: event.id, slug: event.slug, when: 'this-month' },
      query: { venue_id: event.venue.id }
      }" class="title">{{ event.title }}</router-link>

      <router-link v-if="event.id" :to="{ name: 'action-main', params: { id: event.id } }" class="align-right">
        <icon name="ellipsis-h"></icon>
      </router-link>
      <br /><strong>{{ fromNow(event.start_date) }}</strong>, {{ startEndDate(event.start_date, event.end_date) }}
      <div v-if="event.venue">
        <strong>{{ event.type_name }}</strong> &bull; <strong>{{ event.venue.name }}</strong> &bull; {{ event.venue.city.name }}<span v-if="event.venue.city.states">, {{ event.venue.city.states[0].abbr }}</span>
      </div>
    </div>
  </div>

  <div v-else-if="venue">
    <div class="grid is-30" v-if="venue.image_uri">
      <img :src="venue.image_uri" itemprop="image" alt="Photo of Jane Joe">
    </div>
    <div class="grid is-70 address">
      <router-link v-if="venue.id" :to="{ name: 'action-main', params: { id: venue.id } }" class="align-right menu-thingy"><icon name="ellipsis-h"></icon></router-link>
      <router-link :to="{ name: 'venue-single-events', params: { venue_id: venue.id, slug: venue.slug, when: 'this-month' } }" class="title" v-if="venue.id">{{ venue.name }}</router-link>
      <address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        <span v-if="venue.street_address" itemprop="streetAddress">{{ venue.street_address }}</span><br>
        <span itemprop="addressLocality">{{ venue.city.name }}</span>,
        <span v-if="venue.city.states[0]" itemprop="addressRegion">{{ venue.city.states[0].abbr }}</span> <span>{{ venue.zip_code }}</span>
      </address>
      <div v-if="venue.events"><strong>{{ eventCount(venue.events) }}</strong> Events</div>
    </div>
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
  },
  methods: {
    eventCount(events) {
      return events.length;
    }
  }
}
</script>