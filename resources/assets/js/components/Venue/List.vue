<template>
<div>
  <state-select :type="this.$route.name"></state-select>
  <div class="content is-item row grid is-100" v-for="venue in venues.data">
    <action-bar :venue="venue"></action-bar>
  </div>
  <pager :data="venues" :name="'venues'" :meta="{beforePageTitle: 'Venues'}"></pager>
</div>
</template>
<script>
import Pager from '~/components/partials/Pager';
import StateSelect from '~/components/global/StateSelect';
import ActionBar from '~/components/global/ActionBar';

export default {
  components: {
    'pager': Pager,
    'state-select': StateSelect,
    'action-bar': ActionBar
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