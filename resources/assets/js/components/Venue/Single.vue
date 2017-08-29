<template>
<div class="venue content">
  <close class="row is-item grid is-100"></close>
  <div class="top-helper">
    <action-bar :venue="venue" class="grid is-100 row is-item"></action-bar>
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
  <tabs></tabs>
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

import contact from '~/components/Venue/Contact';
import close from '~/components/global/Close';
import actionBar from '~/components/global/ActionBar';
import tabs from '~/components/Event/Tabs';

export default {
  components: {
    contact,
    close,
    actionBar,
    tabs
  },
  data() {
    return {
      venue: { city: { states: [{abbr:''}] } },
      center: { lat: 39.2904, lng: 76.6122 },
      markers: [],
      defaultOptions: {
        mapTypeControl: false,
        scrollwheel: false
      },
      pageTitle: '...'
    }
  },
  metaInfo() {
    return {
      title: this.pageTitle
    }
  },
  computed: {
    venueId() {
      return this.$route.params.venue_id;
    }
  },
  mounted() {
    this.request();
  },
  methods: {
    request() {
      axios.get('/api/venue/'+this.venueId).then(response => {
        this.venue = response.data;
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
  position: relative;
  top: -51px;
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