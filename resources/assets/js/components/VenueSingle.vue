<template>
<div>
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
  <div class="row content">
    <venue-detail
    :id="venue.id"
    :name="venue.name"
    :slug="venue.slug"
    :image="venue.image_uri"
    :description="venue.description"
    :street_address="venue.street_address"
    :city="venue.city.name"
    :state_abbr="venue.city.states[0].abbr"
    :zip_code="venue.zip_code"
    :phone_number="venue.phone_number"
    :email="venue.email"
    :primary_contact="venue.primary_contact"
    :website="venue.website"
    ></venue-detail>
  </div>

  <event-list :title="venue.name" :data="venue.events"></event-list>

</div>
</template>
<script>

// @TODO fix this.
// :city="venue.city.name"
// :state_abbr="venue.city.states[0].abbr"
import * as VueGoogleMaps from 'vue2-google-maps';
import Vue from 'vue';

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyAVD89EibFYXW5pAA7DeVDMv3qfB4gtigg',
    v: '3.27',
  }
});

import VenueDetail from '../components/partials/VenueDetail';
import EventList from '../components/partials/EventList';

export default {
  components: {
    'venue-detail': VenueDetail,
    'event-list': EventList,
  },
  props: ['id', 'slug'],
  data() {
    return {
      venue: { city: { states: [{abbr:''}] } },
      center: { lat: 39.2904, lng: 76.6122 },
      markers: [],
      defaultOptions: {
        gestureHandling: "none",
        mapTypeControl: false,
      },
    }
  },
  mounted() {
    this.request();
  },
  methods: {
    request() {
      axios.get('/api/venue/'+this.id).then(response => {
        this.venue = response.data;
        this.event = response.data;
        this.center.lat = parseInt(response.data.lat);
        this.center.lng = parseInt(response.data.long);
        this.markers = [{
          position: {lat: parseInt(response.data.lat), lng: parseInt(response.data.long)}
        }];
      });
    }
  }
}
</script>