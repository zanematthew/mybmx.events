<template>
<div class="row">
  <div class="nav is-underlined is-tertiary is-spacious">
    <span v-for="item in items">
      <router-link :to="{
        name: route_name,
        params: item.params
      }" class="nav-item">{{ item.title }}</router-link>
    </span>
  </div>

  <div v-if="events.total === 0" class="row is-item">
    <div class="grid is-100">
      <p>There are <strong>No events</strong> at this time. Please refer to the Venue website.</p>
    </div>
  </div>

  <div class="row is-item" v-for="event in events.data">
    <action-bar :event="event"></action-bar>
  </div>

  <pager :data="events"></pager>
</div>
</template>
<script>
import pager from '~/components/global/Pager';
import actionBar from '~/components/global/ActionBar';
import Event from '~/api/Event';

export default {
  components: {
    pager,
    actionBar
  },
  data() {
    return {
      items: [
        {
          title: 'This Month',
          params: { when: 'this-month' }
        },
        {
          title: 'Next Month',
          params: { when: 'next-month' }
        },
        {
          title: 'All Upcoming',
          params: { when: 'upcoming' }
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
      Event.events(
        events => this.events = events,
        this.when,
        _.merge(this.$route.query, {venue_id: this.venueId})
      );
    }
  },
  computed: {
    venueId() {
      return this.$route.params.venue_id || '';
    },
    when() {
      return this.$route.params.when || '';
    }
  },
  watch: {
    '$route' (to, from) {
      this.request();
    }
  }
}
</script>