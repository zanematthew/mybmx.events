'use strict';

import moment from 'moment';
export default {
  methods: {
    fromNow(start_date) {
      return moment(start_date).fromNow();
    },
    startEndDate(start_date, end_date) {
      var startMonthDate = moment(start_date).format("MMM D"),
          year           = moment(end_date).format("YYYY");

      if (start_date == end_date) {
        return startMonthDate + ", " + year;
      } else {
        var endDate = moment(end_date).format("D");
        return startMonthDate + " \u2013 " + endDate + ", " + year;
      }
    },
  }
};
