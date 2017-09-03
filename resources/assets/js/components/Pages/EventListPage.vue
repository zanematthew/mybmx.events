<template>
<div>
  <state :type="this.$route.name"></state>
  <tabs></tabs>
</div>
</template>
<script>
import moment from 'moment';
import state from '~/components/Global/StateSelect';
import tabs from '~/components/Global/Tabs';

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