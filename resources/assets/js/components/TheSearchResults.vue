<template>
<div>
  <!--
  This base form style should be used for;
    - search field
    - action/ add new schedule field
    - schedules/all add new schedule
  -->
  <form class="grid row is-item form icon-inside-field">
    <icon name="search"></icon>
    <input
      type="text"
      placeholder="Search"
      v-model="keyword"
      />
  </form>

  <!-- Tabs -->
  <div class="row nav is-underlined is-tertiary">
    <router-link v-for="item in items" :key="item.id" :to="{
      name: 'search-results',
      params: item.params
    }" class="nav-item">{{ item.title }}</router-link>
  </div>

  <action-bar
    :type="type"
    :item="result"
    :key="result.id"
    v-for="result in results[type]"
    class="row"></action-bar>
</div>
</template>
<script>
import { mapState } from 'vuex';
import actionBar from '~/components/ActionBar';

export default {
  components: {
    actionBar,
  },
  computed: {
    keyword: {
      get () {
        return this.$store.state.search.keyword;
      },
      set (value) {
        this.$store.commit('UPDATE_KEYWORD', value);
      }
    },
    type() {
      return this.$route.params.type;
    }
  },
  data() {
    return {
      // @todo places will be for state, city, zip
      items: [
        {
          title: 'Places',
          params: { type: 'place' }
        },
        {
          title: 'Events',
          params: { type: 'event' }
        },
        {
          title: 'Venues',
          params: { type: 'venue' }
        },
      ],
      results: []
    }
  },
  mounted() {
    this.updateSearchType();
  },
  watch: {
    '$route' (to, from) {
      this.updateSearchType();
    },
    keyword: function () {
      this.search();
    }
  },
  methods: {
    search: _.debounce(function(){
      this.results = [];
      this.$store.dispatch('getSearchResults').then(response => {
        this.results = this.$store.state.search.results;
      });
    }, 250),
    updateSearchType: function () {
      this.$store.commit('UPDATE_SEARCH_TYPE', {
        type: this.$store.state.route.params.type
      });
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../sass/variables";
.icon-inside-field {
  position: relative;
  input[type="text"] {
    padding-left: 30px;
  }
  .fa-icon {
    position: absolute;
    top: 28px;
    left: 30px;
  }
}
.is-tertiary {
  .nav-item {
    float: left;
    width: 33.33%;
    text-align: center;
  }
}
</style>