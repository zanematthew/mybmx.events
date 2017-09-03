<template>
  <div class="content container">
    <close class="grid row is-item" v-on:beforeBack="beforeBack"></close>
    <div v-if="showMenu">
      <div v-if="event.venue.id">
        <router-link :to="{ name: 'share' }" v-on:click.native="hideMenu" class="grid is-100 row is-item">
          <icon name="share-square-o" class="align-icon"></icon> Share
        </router-link>


      <router-link :to="{ name: 'event-single',
        params: { id: event.id, slug: event.slug, when: 'this-month' },
        query: { venue_id: event.venue.id }
        }" class="grid is-100 row is-item">
          <icon name="calendar" class="align-icon"></icon> View Event...
        </router-link>


        <router-link :to="{
        name: 'venue-single-events',
        params: {
          venue_id: event.venue.id,
          slug: event.venue.slug,
          when: 'this-month'
          }
      }" class="grid is-100 row is-item" v-if="event.venue.id">
          <icon name="map-o" class="align-icon"></icon> View Venue...
        </router-link>
        <router-link :to="{ name: 'add-to', params: { id: event.venue.id } }" v-on:click.native="hideMenu" class="grid is-100 row is-item">
          <icon name="list-alt" class="align-icon"></icon> Add to Schedule
        </router-link>
      </div>
    </div>
    <router-view class="main"></router-view>
  </div>
</template>
<script>
import close from '~/components/global/Close';

export default {
  components: {
    close
  },
  data() {
    return {
      event: { venue: { id: '' } },
      showMenu: true
    }
  },
  computed: {
    event_id() {
      return this.$route.params.id;
    }
  },
  mounted() {
    axios.get(`/api/event/${this.event_id}/`).then(response => {
      this.event = response.data;
    });
  },
  methods: {
    hideMenu() {
      this.showMenu = !this.showMenu;
    },
    beforeBack() {
      this.hideMenu();
    }
  }
}
</script>
<style>
.container {
  position: relative;
  top: -51px;
  width: 100%;
}
.close-container {
  background: rgba(255, 255, 255, 0.75);
}
</style>