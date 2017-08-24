<template>
<div>
  <router-link v-if="venue.id" :to="{ name: 'action-main', params: { id: venue.id } }" class="align-right menu-thingy">
    <icon name="ellipsis-h"></icon>
  </router-link>
  <div class="grid is-30" v-if="venue.image_uri">
    <img :src="venue.image_uri" itemprop="image" alt="Photo of Jane Joe">
  </div>
  <div class="grid is-70 address">
    <router-link :to="{ name: 'venue-single', params: { id: venue.id, slug: venue.slug } }" class="title">{{ venue.name }}</router-link>
    <!-- <span v-if="description" itemprop="description"><p>{{ description }}</p></span> -->
    <address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
      <span v-if="venue.street_address" itemprop="streetAddress">{{ venue.street_address }}</span><br>
      <span itemprop="addressLocality">{{ venue.city.name }}</span>,
      <span v-if="venue.city.states[0]" itemprop="addressRegion">{{ venue.city.states[0].abbr }}</span> <span>{{ venue.zip_code }}</span>
    </address>
  </div>
  <div><strong>{{ eventCount(venue.events) }}</strong> Events</div>
</div>
</template>
<script>
import MyMixin from '../../mixin.js';

export default {
  mixins: [MyMixin],
  props: {
    venue: {
      type: Object,
      required: true
    }
  },
  methods: {
    eventCount(events) {
      return events.length;
    }
  }
}
</script>