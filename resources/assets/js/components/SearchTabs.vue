<template>
<div>
  <div class="row nav is-underlined is-tertiary">
    <router-link v-for="item in items" :key="item.id" :to="{
      name: route_name,
      params: item.params,
      query: query
    }" class="nav-item">{{ item.title }}</router-link>
  </div>

  <div class="grid row is-item">
    Search Results here, type: {{ type }}
    <div v-for="result in results" :key="result.title">
      {{ result.title }}<br />
    </div>
  </div>
</div>
</template>
<script>
export default {
  computed: {
    type() {
      return this.$route.params.type;
    },
    query() {
      return this.$route.query;
    },
    results() {
      return this.$store.state.search.results[this.type] || [];
    }
  },
  data() {
    return {
      items: [
        {
          title: 'Places',
          params: { type: 'places' }
        },
        {
          title: 'Events',
          params: { type: 'events' }
        },
        {
          title: 'Venues',
          params: { type: 'venues' }
        },
      ],
      route_name: this.$route.name
    }
  },
  mounted() {
    this.$store.commit('UPDATE_SEARCH_TYPE', {
      type: this.$store.state.route.params.type
    });
  },
  watch: {
    '$route' (to, from) {
      this.$store.commit('UPDATE_SEARCH_TYPE', {
        type: this.$store.state.route.params.type
      });
    }
  }
}
</script>
<style lang="scss">
.is-tertiary {
  .nav-item {
    float: left;
    width: 33.33%;
    text-align: center;
  }
}
</style>