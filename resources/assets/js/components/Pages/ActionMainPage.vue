<template>
  <div>
  <!-- Share -->
  <router-link :to="{ name: 'share' }" class="grid is-100 row is-item">
    <icon name="share-square-o" class="align-icon"></icon> Share
  </router-link>

  <div v-if="type === 'schedule'">
    View Schedule<br />
    Rename Schedule
  </div>

  <router-link v-if="type === 'schedule'" :to="{ name: 'schedule-single-page',
      params: { id: item.id, slug: item.slug } }" class="grid is-100 row is-item" exact>View Events</router-link>

    <!-- View Event -->
    <!-- Pass down the slug, and event.venue.id from the parent, via "meta" -->
    <router-link v-if="type === 'event'" :to="{ name: 'event-single-page',
    params: { id: item.id, slug: item.slug, when: 'this-month' },
    query: { venue_id: item.venue_id }
    }" class="grid is-100 row is-item">
      <icon name="calendar" class="align-icon"></icon> View Event...
    </router-link>

    <!-- View Venue -->
    <router-link :to="{
    name: 'venue-single-events',
    params: {
      venue_id: item.venue_id,
      slug: item.slug,
      when: 'this-month'
      }
    }" class="grid is-100 row is-item" v-if="type === 'venue' || type === 'event'">
      <icon name="map-o" class="align-icon"></icon> View Venue...
    </router-link>

    <!-- @todo Add/Remove -->
    <!-- Event -->
    <router-link v-if="type === 'event'" :to="{ name: 'add-to', params: { id: item.venue_id } }" class="grid is-100 row is-item">
      <icon name="list-alt" class="align-icon"></icon>Add to Schedule
    </router-link>

    <delete-schedule :id="item.id" class="grid is-100 row is-item" v-if="type === 'schedule'"></delete-schedule>
  </div>
</template>
<script>
import { mapGetters } from 'vuex';
import deleteSchedule from '~/components/Global/DeleteSchedule';

export default {
  components: {
    deleteSchedule,
  },
  data() {
    return {
      item: {
        slug: 'default', // Prevents VueJS warning since router.js does not want slug to be ''
        id: 123,
        venue_id: 321
      }
    }
  },
  computed: {
    ...mapGetters([
      'type'
    ])
  },
  mounted() {
    // Build item?
    console.log(this.type);
    let venue_id = this.type === 'venue' ? this.$route.params.id : this.$route.query.venue_id;

    this.item = {
      id: this.$route.params.id,
      slug: this.$route.query.slug,
      name: this.$route.query.name,
      venue_id: venue_id
    };
  },
  metaInfo() {
    return {
      titleTemplate: 'Action | My BMX Events'
    }
  },
}
</script>
