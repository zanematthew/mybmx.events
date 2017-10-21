<template>
<div>
  <tabs-events></tabs-events>
</div>
</template>
<script>
import moment from 'moment';
import tabsEvents from '~/components/TabsEvents';

export default {
  components: {
    tabsEvents
  },
  props: {
    when: {
      type: String,
      required: true
    },
    type: String
  },
  computed: {
    pageNumber() {
      return this.$store.state.route.query.page || 1;
    }
  },
  metaInfo() {
    return {
      title: `${this.getMonthName()} | Page ${this.pageNumber}`,
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