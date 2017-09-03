<template>
<div>
  <state-select :type="this.$route.name"></state-select>
  <div class="row" v-for="venue in venues.data">
    <action-bar :venue="venue"></action-bar>
  </div>
  <pager :data="venues" :name="'venues'" :meta="{beforePageTitle: 'Venues'}"></pager>
</div>
</template>
<script>
import pager from '~/components/Global/Pager';
import stateSelect from '~/components/Global/StateSelect';
import actionBar from '~/components/Global/ActionBar';

export default {
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
  }
}
</script>