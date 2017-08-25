<template>
<div>
  <!-- Events Tabbed -->
  <secondary-nav :items="items"></secondary-nav>
  <div class="content">
    <div class="row is-item" v-for="event in events.data">
      <action-bar :event="event"></action-bar>
    </div>
  </div>
  <pager :data="events" :name="'when'"></pager>
  <!-- Events Tabbed -->
</div>
</template>
<script>
import moment from 'moment';
import Pager from '../../components/partials/Pager';
import ActionBar from '../../components/Event/ActionBar';

export default {
  components: {
    'pager': Pager,
    'action-bar': ActionBar
  },
  props: ['when', 'events'],
  data() {
    return {
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