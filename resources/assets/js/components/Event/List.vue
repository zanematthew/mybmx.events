<template>
<!-- EventList -->
<div>
  <state-select :type="this.$route.name"></state-select>
  <!-- Secondary Nav -->
  <div class="row">
    <div class="nav is-underlined is-tertiary is-spacious">
      <span v-for="link in items">
        <router-link :to="{
          name: 'when',
          params: { 'when': link.when },
          query: appendStateQuery()
        }" class="nav-item">{{ link.name }}</router-link>
      </span>
    </div>
  </div>
  <!-- Secondary Nav -->

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
        <schedule-add-to
          :event="event"
          :initially-scheduled="scheduled"
          :defaultSchedules="defaultSchedules"
          :scheduleId="defaultSchedules"
          ></schedule-add-to>
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
import ScheduleAddTo from '../../components/Schedule/AddTo';
import StateSelect from '../../components/StateSelect';
import MyMixin from '../../mixin.js';

export default {
  mixins: [MyMixin],
  components: {
    'pager': Pager,
    'schedule-add-to': ScheduleAddTo,
    'state-select': StateSelect
  },
  props: ['when'],
  data() {
    return {
      events: {},
      items: [
        {
          name: 'This Month',
          when: 'this-month'
        },
        {
          name: 'Next Month',
          when: 'next-month'
        },
        {
          name: 'All Upcoming',
          when: 'upcoming'
        },
      ],
      currentTab: {},
      scheduled: [],
      defaultSchedules: [],
      mostRecentlyUsedScheduleId: 0
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
    this.getDefaultSchedules();
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
    getDefaultSchedules() {
      axios.get(`/api/schedules/default/`).then(response => {
        this.defaultSchedules = response.data.data;
      }).catch(error => {
      });
    },
    // Retrieve all event IDs on the page
    // currently being viewed.
    getCurrentEventIds() {
      return _.map(this.events.data, 'id');
    }
  }
}
</script>