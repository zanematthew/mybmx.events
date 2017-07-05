<template>
  <div>
    <pager :data="venues" :name="'venues'" :meta="{beforePageTitle: 'Venues'}"></pager>

    <div class="content is-item row" v-for="venue in venues.data">
      <div class="venue-mini">
        <div class="grid is-20">
          <div class="image">
            <img :src="venue.image_uri">
          </div>
        </div>
        <div class="grid is-80">
          <div class="title">
            <router-link :to="{ name: 'venue-single', params: { id: venue.id, slug: venue.slug } }">{{ venue.name }}</router-link>
          </div>
            <div class="body">
              <div>{{ venue.street_address }}<span v-if="venue.city.states">{{ venue.city.name }}, {{ venue.city.states[0].abbr }}</span></div>
              <div><a :href="venue.website" target="_blank">{{ venue.website }}</a></div>
          </div>
          <div><strong>{{ eventCount(venue.events) }}</strong> {{ eventCountText(venue.events) }}</div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Pager from '../components/partials/Pager';

export default {
  components: {
    'pager': Pager,
  },
  props: ['state'],
  data() {
    return {
      venues: {}
      }
  },
  metaInfo: {
    title: 'Venues'
  },
  mounted(){
    this.request();
  },
  methods: {
    eventCount(events) {
      return events.length;
    },
    eventCountText(events) {
      return events.length > 1 ? 'Events' : 'Event';
    },
    request() {
      axios.get('/api/venues/', {
        params: this.$route.query
      }).then(response => {
        this.venues = response.data;
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