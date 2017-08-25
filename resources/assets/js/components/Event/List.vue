<template>
<div>
  <state :type="this.$route.name"></state>
  <tabs :events="events" :when="'when'"></tabs>
</div>
</template>
<script>
import Event from '../../models/Event';
import moment from 'moment';

import state from '../../components/StateSelect';
import tabs from '../../components/Event/Tabs';

export default {
  components: {
    state,
    tabs
  },
  props: {
    when: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      events: {}
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
    }
  }
}
</script>