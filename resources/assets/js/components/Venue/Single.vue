<template>
<div class="venue">
  <close class="row is-item grid is-100"></close>

  <div class="top-helper row">
    <router-link v-if="venue.id" :to="{ name: 'action-main', params: { id: venue.id, landingUrl: landingUrl } }" class="align-right menu-thingy">
      <icon name="ellipsis-h"></icon>
    </router-link>
    <venue-detail :venue="venue"></venue-detail>
  </div>

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

import VenueDetail from '../../components/partials/VenueDetail';
import EventList from '../../components/partials/EventList';
import Close from '../../components/Close';

export default {
  components: {
    'venue-detail': VenueDetail,
    'event-list': EventList,
    'close': Close
  },
  props: ['id', 'slug'],
  data() {
    return {
      venue: { city: { states: [{abbr:''}] } },
      center: { lat: 39.2904, lng: 76.6122 },
      markers: [],
      defaultOptions: {
        mapTypeControl: false,
        scrollwheel: false
      },
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
    request() {
      axios.get('/api/venue/'+this.id).then(response => {
        this.venue = response.data;
        this.event = response.data;
        this.center.lat = parseInt(response.data.lat);
        this.center.lng = parseInt(response.data.long);
        this.markers = [{
          position: {lat: parseInt(response.data.lat), lng: parseInt(response.data.long)}
        }];
        this.pageTitle = this.venue.name;
      });
    }
  }
}
</script>
<style lang="scss" scoped>
.close-container {
  opacity: .75;
  background: #fff;
}
.venue {
  position: absolute;
}
.title {
  margin-bottom: 10px;
}
.top-helper {
  position: relative;
  .menu-thingy {
    position: absolute;
    top: 0;
    right: 0;
    margin: 20px;
  }
}
</style>