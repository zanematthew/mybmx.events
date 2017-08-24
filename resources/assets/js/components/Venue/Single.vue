<template>
<div class="venue content">
  <close class="row is-item grid is-100"></close>

  <div class="top-helper">
    <venue-action-bar :venue="venue" class="grid is-100 row is-item"></venue-action-bar>
    <contact :venue="venue" class="grid is-100 row is-item"></contact>
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

  <div class="row is-item grid is-100">
    <h2 class="title">{{ venue.name }}</h2>
  </div>
  <div class="row is-item" v-for="event in venue.events">
    <action-bar :event="event"></action-bar>
  </div>
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

import contact from '../../components/Venue/Contact';
import close from '../../components/Close';
import ActionBar from '../../components/Event/ActionBar';
import VenueActionBar from '../../components/Venue/ActionBar';

export default {
  components: {
    contact,
    close,
    'action-bar': ActionBar,
    'venue-action-bar': VenueActionBar,
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