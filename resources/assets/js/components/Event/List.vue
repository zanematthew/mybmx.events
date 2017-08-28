<template>
<div>
  <state :type="this.$route.name"></state>
  <tabs></tabs>
</div>
</template>
<script>
import Event from '~/models/Event';
import moment from 'moment';

import state from '~/components/StateSelect';
import tabs from '~/components/Event/Tabs';

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
  metaInfo() {
    return {
      title: this.getMonthName(),
      titleTemplate: '%s | My BMX Events'
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