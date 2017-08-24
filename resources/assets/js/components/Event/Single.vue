<template>
<div id="event-single" class="content">
  <close class="row is-item grid is-100"></close>
  <action-bar :event="event" class="row is-item"></action-bar>

  <!-- Event Detail -->
  <div class="row is-item" v-if="event.fee">
    <div class="grid is-100">
      <strong>Fee</strong> {{ formatCurrency(event.fee) }}<br />
      <strong>Registration Start</strong> {{ formatTime(event.start_date + ' ' + event.registration_start_time) }}<br />
      <strong>Registration End</strong> {{ formatTime(event.start_date + ' ' + event.registration_end_time) }}<br />
    </div>
  </div>

  <div class="row is-item" v-if="event.event_schedule_uri">
    <div class="grid is-100">
      <a :href="event.event_schedule_uri" target="_blank">Schedule (PDF)</a>,
      <a :href="event.flyer_uri" target="_blank">Flier (PDF)</a>
    </div>
  </div>
  <!-- Event detail -->

  <!-- Map -->
  <div class="row">
    <gmap-map
      :options="defaultOptions"
      :draggable="false"
      :center="center"
      :zoom="7"
      style="width: 100%; height: 300px"
    >
      <gmap-marker
        :key="index"
        v-for="(m, index) in markers"
        :position="m.position"
        @click="center=m.position"
      ></gmap-marker>
    </gmap-map>
  </div>

  <!-- Venue Detail -->
  <contact :venue="event.venue" class="row is-item grid is-100"></contact>

  <!-- Upcoming Events this month -->
  <div class="row is-item grid is-100">
    <h2 class="title">Events at this Venue</h2>
  </div>
  <div class="row is-item" v-for="event in relatedEvents.data">
    <action-bar :event="event"></action-bar>
  </div>

</div>
</template>

<script>
import MyMixin from '../../mixin.js';
import moment from 'moment';
import contact from '../../components/Venue/Contact';
import EventActionBar from '../../components/Event/ActionBar';

import Close from '../../components/Close';
import * as VueGoogleMaps from 'vue2-google-maps';
import Vue from 'vue';

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyAVD89EibFYXW5pAA7DeVDMv3qfB4gtigg',
    v: '3.27',
  }
});

var numeral = require('numeral');

export default {
  mixins: [MyMixin],
  components: {
    contact,
    'close': Close,
    'action-bar': EventActionBar
  },
  props: ['id', 'slug'],
  data() {
    return {
      event: { venue: { city: { states: '' } } },
      center: { lat: 39.2904, lng: 76.6122 },
      markers: [],
      defaultOptions: {
        scrollwheel: false,
        mapTypeControl: false,
      },
      relatedEvents: [],
      pageTitle: '...',
      landingUrl: `${window.location.origin}${window.location.pathname}${window.location.search}`
    }
  },
  metaInfo() {
    return {
      title: this.pageTitle
    }
  },
  mounted() {
    this.request();
  },
  methods: {
    formatTime(time) {
      return moment(time).format('h:mm a');
    },
    formatCurrency(number) {
      return numeral(number).format('$0,0[.]00');
    },
    request() {
      axios.get('/api/event/'+this.id+'/').then(response => {
        this.event = response.data;
        this.center.lat = parseInt(response.data.venue.lat);
        this.center.lng = parseInt(response.data.venue.long);
        this.markers = [{
          position: {lat: parseInt(response.data.venue.lat), lng: parseInt(response.data.venue.long)}
        }];
        this.pageTitle = `${this.event.venue.name} // ${this.event.title}`;
        return response.data;
      }).then(response => {
        axios.get('/api/events/', {
          params: {
            this_month: true,
            venue_id: response.venue_id,
          }
        }).then(response => {
          this.relatedEvents = response.data;
        });
      });
    }
  },
  watch: {
    '$route' (to, from) {
      this.request();
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../../sass/variables";
.vue-map-container {
  border: 1px solid $light-gray;
}
.close-container {
  opacity: .75;
  background: #fff;
}
#event-single {
  position: absolute;
  top: 0;
}
.title {
  margin-bottom: 10px;
}
</style>
