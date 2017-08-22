'use strict';

import moment from 'moment';
export default {
  methods: {
    fromNow(start_date) {
      return moment(start_date).fromNow();
    },
    startEndDate(start_date, end_date) {
      if (typeof start_date == 'undefined' || typeof end_date == 'undefined') {
        return;
      }

      var startMonthDate = moment(start_date).format("MMM D");

      if (!end_date){
        return startMonthDate + ", " + moment(start_date).format("YYYY");
      } else if (start_date == end_date) {
        return startMonthDate + ", " + moment(end_date).format("YYYY");
      } else {
        var endDate = moment(end_date).format("D");
        return startMonthDate + " \u2013 " + endDate + ", " + moment(end_date).format("YYYY");
      }
    },
    appendStateQuery() {
      if (typeof this.$route.query.states != 'undefined') {
        return { states: this.$route.query.states };
      }
    },
    getLandingUrl() {
      return `${window.location.origin}${window.location.pathname}${window.location.search}`;
    }
  }
};
