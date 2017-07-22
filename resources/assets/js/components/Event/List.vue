<template>
<!-- EventList -->
<div>
  <state-select :type="this.$route.name"></state-select>
  <secondary-nav :items="items"></secondary-nav>
  <list-items :events="events.data"></list-items>
  <pager :data="events" :name="'when'"></pager>
</div>
<!--  EventList -->
</template>
<script>
import Event from '../../models/Event';
import moment from 'moment';
import StateSelect from '../../components/StateSelect';
import ListItems from '../../components/Event/ListItems';
import Pager from '../../components/partials/Pager';

export default {
  components: {
    'state-select': StateSelect,
    'list-items': ListItems,
    'pager': Pager
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