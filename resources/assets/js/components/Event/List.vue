<template>
<!-- EventList -->
<div>
  <state-select :type="this.$route.name"></state-select>
  <secondary-nav :items="items"></secondary-nav>
  <div class="content row is-item" v-for="event in events.data">
    <div class="event-mini">
      <div class="grid is-80">
        <div class="title">
          <router-link :to="{ name: 'event-single', params: { id: event.id, slug: event.slug } }">
            {{ event.title }}
          </router-link>
        </div>
        <div>
          <strong>{{ fromNow(event.start_date) }}</strong>, {{ startEndDate(event.start_date, event.end_date) }}
        </div>
        <div class="body">
          <div>
            <strong>{{ event.type_name }}</strong> &bull; <strong>{{ event.venue.name }}</strong> &bull; {{ event.venue.city.name }}<span v-if="event.venue.city.states">, {{ event.venue.city.states[0].abbr }}</span>
          </div>
        </div>
      </div>
      <div class="grid is-20 align-right">
        <schedule-add-to-master
          :event="event"
          :initially-scheduled="scheduled"
          :schedule-master-id="masterSchedule.id"
          ></schedule-add-to-master>
      </div>
    </div>
  </div>
  <pager :data="events" :name="'when'"></pager>
</div>
<!--  EventList -->
</template>
<script>
import Event from '../../models/Event';
import moment from 'moment';
import Pager from '../../components/partials/Pager';
import ScheduleAddToMaster from '../../components/Schedule/AddToMaster';
import StateSelect from '../../components/StateSelect';
import MyMixin from '../../mixin.js';

export default {
  mixins: [MyMixin],
  components: {
    'pager': Pager,
    'schedule-add-to-master': ScheduleAddToMaster,
    'state-select': StateSelect
  },
  props: ['when'],
  data() {
    return {
      events: {},
      items: [
        {
          title: 'This Month',
          name: 'when',
          params: { when: 'this-month' }
        },
        {
          title: 'Next Month',
          name: 'when',
          params: { when: 'next-month' }
        },
        {
          title: 'All Upcoming',
          name: 'when',
          params: { when: 'upcoming' }
        },
      ],
      currentTab: {},
      scheduled: [],
      mostRecentlyUsedScheduleId: 0,
      masterSchedule: {}
    }
  },
  metaInfo() {
    return {
      title: this.getMonthName(),
      titleTemplate: '%s | My BMX Events'
    }
  },
  mounted() {
    Event.events(events => this.events = events, this.$route.params.when, this.$route.query);
    this.getScheduled();
    this.getMasterScheduleId();
  },
  watch: {
    '$route' (to, from) {
      Event.events(events => this.events = events, this.when, this.$route.query);
    }
  },
  methods: {
    getMonthName() {
      var monthName;
      if (this.when == 'this-month') {
        monthName = moment().format('MMMM');
      } else if (this.when == 'next-month') {
        monthName = moment().add(1, 'month').format('MMMM');
      } else if (this.when == 'upcoming') {
        monthName = 'Upcoming';
      }
      return monthName;
    },
    getScheduled() {
      axios.get(`/api/schedules/scheduled/`).then(response => {
        this.scheduled = response.data;
      });
    },
    // Retrieve all event IDs on the page
    // currently being viewed.
    getCurrentEventIds() {
      return _.map(this.events.data, 'id');
    },
    getMasterScheduleId() {
      axios.get('/api/schedules/master/').then(response => {
        this.masterSchedule = response.data;
      });
    }
  }
}
</script>