<template>
<div>
  <state-select :type="this.$route.name"></state-select>
  <secondary-nav :items="items"></secondary-nav>
  <div class="content">
    <div class="row is-item" v-for="event in events.data">
      <action-bar :event="event"></action-bar>
    </div>
  </div>
  <pager :data="events" :name="'when'"></pager>
</div>
</template>
<script>
import Event from '../../models/Event';
import moment from 'moment';
import StateSelect from '../../components/StateSelect';
import Pager from '../../components/partials/Pager';
import ActionBar from '../../components/Event/ActionBar';

export default {
  components: {
    'state-select': StateSelect,
    'pager': Pager,
    'action-bar': ActionBar
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
      currentTab: {}
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
    getCurrentEventIds() {
      return _.map(this.events.data, 'id');
    }
  }
}
</script>