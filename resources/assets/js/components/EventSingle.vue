<template>
<div id="event-single">
  <!-- Event Mini -->
  <div class="content row is-item">
    <div class="event-mini">
      <!--  All -->
      <div class="grid is-40 is-mobile-100">
        <div class="title">
          <router-link :to="{ name: 'event-single', params: { id: event.id, slug: event.slug } }">{{ event.title }}</router-link>
        </div>
        <div> <strong>{{ fromNow(event.start_date) }}</strong>, {{ startEndDate(event.start_date, event.end_date) }}</div>
        <div class="body">
          <div>
            <strong>{{ event.type_name }}</strong> &bull; <strong>{{ event.venue.name }}</strong> &bull; {{ event.venue.city.name }}<span v-if="event.venue.city.states">, {{ event.venue.city.states[0].abbr }}</span>
          </div>
        </div>
      </div>
      <!-- Locals -->
      <div class="grid is-30 is-mobile-100" v-if="event.fee">
        <strong>Fee</strong> {{ formatCurrency(event.fee) }}<br />
        <strong>Registration Start</strong> {{ formatTime(event.start_date + ' ' + event.registration_start_time) }}<br />
        <strong>Registration End</strong> {{ formatTime(event.start_date + ' ' + event.registration_end_time) }}<br />
      </div>
      <!-- Nationals -->
      <div class="grid is-30 is-mobile-100" v-if="event.event_schedule_uri">
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
  <event-list :title="'Related Events'" :data="relatedEvents.data"></event-list>

</div>
</template>

<script>
import MyMixin from '../mixin.js';
import moment from 'moment';
import VenueDetail from '../components/partials/VenueDetail';
import EventList from '../components/partials/EventList';
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
    'venue-detail': VenueDetail,
    'event-list': EventList,
  },
  props: ['id', 'slug'],
  data() {
    return {
      event: { venue: { city: { states: '' } } },
      center: { lat: 39.2904, lng: 76.6122 },
      markers: [],
      defaultOptions: {
        gestureHandling: "none",
        mapTypeControl: false,
      },
      relatedEvents: [],
      pageTitle: '...'
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
        this.pageTitle = this.event.venue.name + ' // ' + this.event.title;
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
@import "../../sass/variables";
.vue-map-container {
  border: 1px solid $light-gray;
}
</style>
