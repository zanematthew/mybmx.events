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
      :placeholder="placeholder"
      v-model="text"
      />
  </form>

  <!-- Tabs -->
  <div class="row nav is-underlined is-tertiary">
    <router-link v-for="item in items" :key="item.id" :to="{
      name: 'search-results',
      params: item.params
    }" class="nav-item">{{ item.title }}</router-link>
  </div>

  <div v-if="text ==='' && resultsCount >0">
    <action-bar
        :type="type"
        :item="result"
        :key="result.id"
        v-for="result in results[type]"
        class="row"></action-bar>
  </div>
  <div v-else-if="text">
    <div v-if="resultsCount">
      <action-bar
        :type="type"
        :item="result"
        :key="result.id"
        v-for="result in results[type]"
        class="row"></action-bar>
    </div>
    <div class="grid row is-item" v-else>
      No, results.
    </div>
  </div>
  <div class="link grid row is-item"
    v-else
    v-on:click.prevent="currentLocationResults">
    <icon name="location-arrow"></icon> Near current location.
  </div>
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
    text: {
      get () {
        return this.$store.state.search.text;
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
      results: [],
      placeholder: '',
      resultsCount: 0,
    }
  },
  mounted() {
    this.updateSearchType();
    this.updatePlaceholder();
  },
  watch: {
    '$route' (to, from) {
      this.updateSearchType();
      this.updatePlaceholder();
    },
    text: function () {
      this.search();
    }
  },
  methods: {
    search: _.debounce(function(){

      if (_.isEmpty(this.text)) {
        this.results = [];
        return;
      }
      this.results = [];
      this.resultsCount = 1;
      this.$store.dispatch('getSearchResults').then(response => {
        this.results = this.$store.state.search.results;
        this.resultsCount = this.results[this.type].length;
      });
    }, 500),
    updateSearchType () {
      this.$store.commit('UPDATE_SEARCH_TYPE', {
        type: this.$store.state.route.params.type
      });
    },
    updatePlaceholder() {
      switch (this.type) {
        case 'event':
          this.placeholder = 'Local Race';
          break;
        case 'place':
          this.placeholder = 'Maryland';
          break;
        case 'venue':
          this.placeholder = 'Chesapeake BMX';
          break;
        default:
          this.placeholder = 'Search';
      }
    },
    currentLocationResults() {
      this.$store.commit('UPDATE_KEYWORD', 'Current Location');
    },
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
.link {
  color: $primary-focus-color;
  .fa-icon {
    margin-right: 10px;
    color: $primary-focus-color;
  }
}
</style>