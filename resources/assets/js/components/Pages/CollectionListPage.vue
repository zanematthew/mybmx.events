<template>
  <div>
      <router-link v-for="item in item_types" :key="item.id" :to="{ name: 'collection-type', params: { item_type: item.type } }" class="grid is-100 row is-item title">{{ item.name }}</router-link>
  </div>
</template>
<script>
export default {
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
  metaInfo() {
    return {
      titleTemplate: 'Collections | My BMX Events'
    }
  },
}
</script>