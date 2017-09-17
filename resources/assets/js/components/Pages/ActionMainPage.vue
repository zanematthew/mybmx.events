<template>
  <div>
    <router-link :to="{ name: 'share' }" class="grid is-100 row is-item">
      <icon name="share-square-o" class="align-icon"></icon> Share
    </router-link>
    <router-link v-if="event.id" :to="{ name: 'event-single-page',
    params: { id: event.id, slug: event.slug, when: 'this-month' },
    query: { venue_id: event.venue.id }
    }" class="grid is-100 row is-item">
      <icon name="calendar" class="align-icon"></icon> View Event...
    </router-link>
    <router-link :to="{
    name: 'venue-single-events',
    params: {
      venue_id: event.venue.id,
      slug: event.venue.slug,
      when: 'this-month'
      }
    }" class="grid is-100 row is-item" v-if="event.venue.id">
      <icon name="map-o" class="align-icon"></icon> View Venue...
    </router-link>
    <!-- @todo Add/Remove -->
    <router-link :to="{ name: 'add-to', params: { id: event.venue.id } }" class="grid is-100 row is-item">
      <icon name="list-alt" class="align-icon"></icon> Add to Schedule
    </router-link>
  </div>
</template>
<script>
export default {
  data() {
    return {
      event: { venue: { id: '' } }
    }
  },
  computed: {
    event_id() {
      return this.$route.params.id;
    }
  },
  mounted() {
    axios.get(`/api/event/${this.event_id}/`).then(response => {
      this.event = response.data;
    });
  }
}
</script>
