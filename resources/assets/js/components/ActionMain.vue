<template>
  <div class="content container">
    <div class="row is-item" v-on:click="showMenu = !showMenu">
      <close class="grid is-100"></close>
    </div>
    <div v-if="showMenu">
      <router-link :to="{ name: 'share' }" v-on:click.native="isActive" class="grid is-100 row is-item">
        <icon name="share-square-o"></icon> Share
      </router-link>
      <router-link :to="{ name: 'event-single', params: { id: id } }" v-on:click.native="isActive" class="grid is-100 row is-item">
        <icon name="calendar"></icon> View Event...
      </router-link>
      <router-link :to="{ name: 'venue-single', params: { id: event.venue.id } }" v-on:click.native="isActive" class="grid is-100 row is-item">
        <icon name="map-o"></icon> View Venue...
      </router-link>
    </div>
    <router-view class="main"></router-view>
  </div>
</template>
<script>
import close from './../components/Close';

export default {
  components: {
    close
  },
  data() {
    return {
      id: this.$route.params.id,
      event: { venue: { id: '' } },
      showMenu: true
    }
  },
  mounted() {
    axios.get(`/api/event/${this.id}/`).then(response => {
      this.event = response.data;
    });
  },
  methods: {
    isActive() {
      this.showMenu = !this.showMenu;
    }
  }
}
</script>
<style>
.container {
  position: absolute;
  top: 0;
  width: 100%;
  background: rgba(255, 255, 255, 0.75);
}
</style>