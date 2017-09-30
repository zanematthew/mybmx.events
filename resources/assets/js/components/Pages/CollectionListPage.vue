<template>
  <div>
    <div v-for="(value, key, index) in items" :key="key">
        <div class="title row grid is-item">{{ key }}</div>
        <action-bar :type="key" :item="collected" v-for="collected in value" :key="collected.id" class="row"></action-bar>
    </div>
  </div>
</template>
<script>
import actionBar from '~/components/Global/ActionBar';

export default {
  components: {
    actionBar
  },
  data() {
    // @todo derive from vuex Library as a getter getLibraryItemTypes
    return {
      item_types: [
        {
          type: 'event',
          name: 'Event'
        },
        {
          type: 'venue',
          name: 'Venue',
        },
        {
          type: 'schedule',
          name: 'Schedule'
        }
      ]
    }
  },
  computed: {
    items() {
      return this.$store.state.library || [];
    }
  },
  metaInfo() {
    return {
      titleTemplate: 'Collections | My BMX Events'
    }
  },
}
</script>