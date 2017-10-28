<template>
<div>
  <div class="row nav is-underlined is-tertiary">
    <router-link v-for="item in items" :key="item.id" :to="{
      name: route_name,
      params: item.params,
      query: query
    }" class="nav-item">{{ item.title }}</router-link>
  </div>

  <div class="grid row is-item">
    Search Results here, type: {{ type }}
  </div>
  <!-- <action-bar :type="'event'" :item="event" :key="event.id" v-for="event in events.data" class="row"></action-bar> -->

  <!-- <the-pager :data="events"></the-pager> -->
</div>
</template>
<script>
import thePager from '~/components/ThePager';
import actionBar from '~/components/ActionBar';
import Event from '~/api/Event';

export default {
  computed: {
    venue_id() {
      return this.$route.params.venue_id;
    },
    type() {
      return this.$route.params.type;
    },
    query() {
      return this.$route.query;
    }
  },
  components: {
    thePager,
    actionBar
  },
  data() {
    return {
      // places
      // events
      // venues
      // people
      // tags
      items: [
        {
          title: 'Places',
          params: { type: 'places' }
        },
        {
          title: 'Events',
          params: { type: 'events' }
        },
        {
          title: 'Venues',
          params: { type: 'venues' }
        },
      ],
      events: {},
      route_name: this.$route.name
    }
  },
  mounted() {
    this.request();
  },
  methods: {
    request() {
      // @todo once we add the option to add events
      // this needs to be handled via the event store.
      // Event.events(
      //   events => this.events = events,
      //   this.when,
      //   _.merge(this.$route.query, {venue_id: this.venue_id})
      // );
    }
  },
  watch: {
    '$route' (to, from) {
      this.request();
    }
  }
}
</script>
<style lang="scss">
.is-tertiary {
  .nav-item {
    float: left;
    width: 33.33%;
    text-align: center;
  }
}
</style>