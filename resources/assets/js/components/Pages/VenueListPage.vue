<template>
  <div v-if="venues.data">
    <state-select :type="this.$route.name"></state-select>
    <action-bar :type="'venue'" :item="venue" class="row" v-for="venue in venues.data" :key="venue.id"></action-bar>
    <pager :data="venues" :name="'venues'" :meta="{beforePageTitle: 'Venues'}"></pager>
  </div>
  <div v-else class="align-center row is-item grid is-100">
    <icon name="refresh" spin></icon>
  </div>
</template>
<script>
import pager from '~/components/Global/Pager';
import stateSelect from '~/components/Global/StateSelect';
import actionBar from '~/components/Global/ActionBar';
import MyMixin from '~/mixin.js';

export default {
  mixins: [MyMixin],
  components: {
    pager,
    stateSelect,
    actionBar
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
      // @todo move to api/Venues.js
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
  },
  computed: {
    pageNumber() {
      return this.$store.state.route.query.page || 1;
    }
  },
  metaInfo() {
    return {
      title: `Venues | Page ${this.pageNumber}`,
      titleTemplate: '%s | My BMX Events'
    }
  }
}
</script>