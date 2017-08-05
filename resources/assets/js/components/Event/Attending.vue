<template>
  <div>
    <list-items :events="scheduled.events"></list-items>
  </div>
</template>
<script>
import ListItems from '../../components/Event/ListItems';

export default {
  components: {
    'list-items': ListItems
  },
  computed: {
    scheduled() {
      return this.$store.state.user.schedule.master;
    }
  },
  mounted() {
    this.$store.dispatch('fetchAllScheduledEvents');
  }
}
</script>