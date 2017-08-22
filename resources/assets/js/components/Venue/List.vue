<template>
<div>
  <state-select :type="this.$route.name"></state-select>
  <div class="content is-item row grid is-100" v-for="venue in venues.data">
    <venue-action-bar :venue="venue"></venue-action-bar>
  </div>
  <pager :data="venues" :name="'venues'" :meta="{beforePageTitle: 'Venues'}"></pager>
</div>
</template>
<script>
import Pager from '../../components/partials/Pager';
import StateSelect from '../../components/StateSelect';
import VenueActionBar from '../../components/Venue/ActionBar';

export default {
  components: {
    'pager': Pager,
    'state-select': StateSelect,
    'venue-action-bar': VenueActionBar
  },
  props: ['state'],
  data() {
    return {
      venues: {}
      }
  },
  mounted() {
    this.request();
  },
  methods: {
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