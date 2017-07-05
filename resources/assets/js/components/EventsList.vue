<template>
<div>
  <div class="row">
    <div class="nav is-underlined is-tertiary is-spacious">
      <span v-for="link in items">
        <router-link :to="{
          name: 'events',
          params: link.params,
          query: appendStateQuery()
        }" class="nav-item">{{ link.name }}</router-link>
      </span>
    </div>

    <pager :data="events" :name="'events'"></pager>

  </div>
  <div class="content row is-item" v-for="event in events.data">
    <div class="event-mini">
      <div class="grid is-100">
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
import Pager from '../components/partials/Pager';
import MyMixin from '../mixin.js';

export default {
  mixins: [MyMixin],
  components: {
    'pager': Pager
  },
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
  metaInfo() {
    var month;
    if (this.when == 'this-month') {
      month = moment().format('MMMM');
    } else if (this.when == 'next-month') {
      month = moment().add(1, 'month').format('MMMM');
    } else if (this.when == 'upcoming') {
      month = 'Upcoming';
    }

    return {
      title: month,
      titleTemplate: '%s - BMX Events | My BMX Events'
    }
  },
  mounted() {
    Event.events(events => this.events = events, this.$route.params.when, this.$route.query);
    this.setCurrentTab();
  },
  watch: {
    '$route' (to, from) {
      Event.events(events => this.events = events, this.when, this.$route.query);
      this.setCurrentTab();
    }
  },
  methods: {
    appendStateQuery() {
      if (typeof this.$route.query.states != 'undefined') {
        return { states: this.$route.query.states };
      }
    },
    setCurrentTab() {
      for (let i of this.items) {
        if (i.when == this.when){
          this.currentTab = i;
        }
      }
    }
  }
}
</script>
