<template>
<div id="event-single">
  <!-- Event Mini -->
  <div class="content row is-item">
    <div class="event-mini">
      <!--  All -->
      <div class="grid is-40">
        <div class="title">
          <router-link :to="{ name: 'event-single', params: { id: event.id, slug: event.slug } }">{{ event.title }}</router-link>
        </div>
        <div><strong>{{ fromNow(event.start_date) }}</strong>, {{ startEndDate(event.start_date, event.end_date) }}</div>
        <div class="body">
          <div>
            <strong>{{ event.type_name }}</strong> &bull; <strong>{{ event.venue.name }}</strong> &bull; {{ event.venue.city.name }}<span v-if="event.venue.city.states">, {{ event.venue.city.states[0].abbr }}</span>
          </div>
        </div>
      </div>
      <!-- Locals -->
      <div class="grid is-30" v-if="event.fee">
        <strong>Fee</strong> {{ formatCurrency(event.fee) }}<br />
        <strong>Registration Start</strong> {{ formatTime(event.start_date + ' ' + event.registration_start_time) }}<br />
        <strong>Registration End</strong> {{ formatTime(event.start_date + ' ' + event.registration_end_time) }}<br />
      </div>
      <!-- Nationals -->
      <div class="grid is-30" v-if="event.event_schedule_uri">
        <a :href="event.event_schedule_uri" target="_blank">Schedule (PDF)</a>,
        <a :href="event.flyer_uri" target="_blank">Flier (PDF)</a>
      </div>
    </div>
  </div>

  <!-- Map -->
  <div class="content row">
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
<div v-if="event.venue.city.states">
  <venue-detail
    :id="event.venue.id"
    :name="event.venue.name"
    :slug="event.venue.slug"
    :image="event.venue.image_uri"
    :description="event.venue.description"
    :street_address="event.venue.street_address"
    :city="event.venue.city.name"
    :state_abbr="event.venue.city.states[0].abbr"
    :zip_code="event.venue.zip_code"
    :phone_number="event.venue.phone_number"
    :email="event.venue.email"
    :primary_contact="event.venue.primary_contact"
    :website="event.venue.website"
  ></venue-detail>
</div>

  <!-- Upcoming Events this month -->
  <div class="row content is-item is-condensed">
    <h2 class="title">Related Events</h2>
  </div>
  <div class="content row is-item is-condensed" v-for="event in relatedEvents.data">
    <h1><router-link :to="{ name: 'event-single', params: { id: event.id, slug: event.slug }}">{{ event.title }}</router-link> {{ fromNow(event.start_date) }}, {{ startEndDate(event.start_date, event.end_date) }}</h1>
  </div>
</div>
</template>

<script>
import moment from 'moment';
import Event from '../models/Event';
import VenueDetail from '../components/partials/VenueDetail';
import * as VueGoogleMaps from 'vue2-google-maps';
import Vue from 'vue';

Vue.use(VueGoogleMaps, {
  load: {
    key: '',
    v: '3.27',
  }
});

// @TODO move to be global;
var numeral = require('numeral');

// @TODO move to be global;
var SocialSharing = require('vue-social-sharing');
Vue.use(SocialSharing);

export default {
  components: {
    'venue-detail': VenueDetail
  },
  props: ['id', 'slug'],
  data() {
    return {
      // https://stackoverflow.com/questions/40713905/deeply-nested-data-objects-in-vuejs
      event: { venue: { city: { states: '' } } },
      center: { lat: 10, lng: -10 },
      markers: [],
      defaultOptions: {
        gestureHandling: "none",
        // streetViewControl: false,
        // panControlOptions: false,
        mapTypeControl: false,
      },
      relatedEvents: []
    }
  },
  mounted() {
    // Event.single( event => this.event = event, this.id );
    this.request();
  },
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
    formatTime(time) {
      return moment(time).format('h:mm a');
    },
    formatCurrency(number) {
      return numeral(number).format('$0,0[.]00');
    },
    request() {
      var self = this;
      axios.get('/api/event/'+this.id+'/').then((response) => {
        self.event = response.data;
        self.center.lat = parseInt(response.data.venue.lat);
        self.center.lng = parseInt(response.data.venue.long);
        self.markers = [{
          position: {lat: parseInt(response.data.venue.lat), lng: parseInt(response.data.venue.long)}
        }];
        return response.data;
      }).then((response) => {
        axios.get('/api/events/', {
          params: {
            this_month: true,
            venue_id: response.venue_id,
          }
        }).then((response) => {
          self.relatedEvents = response.data;
        });
      });
    },
    shareUrl() {
      return window.location.href;
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
@import "../../sass/variables";
.vue-map-container {
  border: 1px solid $light-gray;
}
</style>
