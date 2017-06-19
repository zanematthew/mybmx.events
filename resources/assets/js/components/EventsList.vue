<template>
<div>
  <div class="row">
    <div class="nav is-underlined is-tertiary is-spacious">
      <span v-for="link in items">
        <router-link :to="{ name: 'events', params: link.params, }" class="nav-item" exact>{{ link.name }}</router-link>
      </span>
    </div>
  </div>
  <div class="content row is-item" v-for="event in events.data">
    <div class="event-mini">
      <div class="grid is-20">
        <div class="image">
          <img :src="event.venue.image_uri">
        </div>
      </div>
      <div class="grid is-80">
        <div class="title">
          <router-link :to="{ name: 'event-single', params: { id: event.id, slug: event.slug } }">{{ event.title }}</router-link>
        </div>
          <div><strong>{{ fromNow(event.start_date) }}</strong>, {{ startEndDate(event.start_date, event.end_date) }}</div>
          <div class="body">
            <div><strong>{{ event.type_name }}</strong> &bull; <strong>{{ event.venue.name }}</strong> &bull; {{ event.venue.city.name }}<span v-if="event.venue.city.states">, {{ event.venue.city.states[0].abbr }}</span></div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import Event from '../models/Event';
import moment from 'moment';
export default {
  props: ['when'],
  data() {
    return {
      events: [],
      items: [
        {
          name: 'This Month',
          params: {
            when: 'this-month'
          }
        },
        {
          name: 'Next Month',
          params: {
            when: 'next-month'
          }
        },
        {
          name: 'All Upcoming',
          params: {
            when: 'upcoming'
          }
        },
      ]
    }
  },
  mounted() {
    Event.events(events => this.events = events, 'this-month');
  },
  methods: {
    fromNow(start_date) {
      return moment(start_date).fromNow();
    },
    startEndDate(start_date, end_date) {
      var startMonthDate = moment(start_date).format("MMM D"),
          year           = moment(end_date).format("YYYY");

      if (start_date == end_date) {
        return startMonthDate + ", " + year;
      } else {
        var endDate = moment(end_date).format("D");
        return startMonthDate + " \u2013 " + endDate + ", " + year;
      }
    },
  },
  watch: {
    '$route' (to, from) {
      Event.events(events => this.events = events, this.when);
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../sass/variables";
.is-tertiary {
  border-bottom: 1px solid $light-gray;
}
</style>
