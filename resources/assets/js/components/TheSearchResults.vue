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
      placeholder="Search..."
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

  <!-- Initial State -->
  <!-- When the search box is empty, present the -->
  <!-- user with the current location icon -->
  <div
    v-if="text === ''"
    class="link grid row is-item"
    v-on:click.prevent="setCurrentLocationUpdateText">
    <icon name="location-arrow"></icon> {{ locationText.current }}
  </div>

  <!-- Has result state -->
  <!-- When we have results display the action-bar -->
  <!-- for each item in our result set. -->
  <action-bar
    v-if="resultsCount > 0"
    :type="type"
    :item="result"
    :key="result.id"
    v-for="result in results[type]"
    class="row"></action-bar>

  <!-- Has no result state -->
  <!-- If we have text, and no results are found -->
  <!-- then display a message. -->
  <div
    v-if="text && resultsCount === 0"
    class="grid row is-item">
    No results
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
      // @todo move count to be part of state.
      // Restructure results state as;
      // search.results[type] = [ count => 0, items = [] ];
      resultsCount: 0,
      locationText: {
        current: '',
        action: 'Near current location',
        updating: 'Getting current location...'
      },
    }
  },
  mounted() {
    this.toggleLocationText();
    this.updateSearchType();
  },
  watch: {
    '$route' (to, from) {
      this.updateSearchType();
      // If we have results for a given type
      // and the search text has _not_ changed
      // there is no need to re-run the search.
      if (_.isUndefined(this.$store.state.search.results[this.type])) {
        this.search();
      }
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

    /**
     * Simply put, our search type (sort of) maps to a model in the API.
     *
     * Note; "places", is not a model, its just a flag to narrow search
     * results based on a place, i.e., city, state.
     */
    updateSearchType () {
      this.$store.commit('UPDATE_SEARCH_TYPE', {
        type: this.$store.state.route.params.type
      });
    },

    /**
     * Next to the current location icon we want to display a message,
     * that conveys the action that will take place, or the action
     * that currently taking place. This action can be one of;
     * "Near current location"
     * "Getting current location..."
     */
    toggleLocationText() {
      if (_.isEmpty(this.locationText.current)) {
        this.locationText.current = this.locationText.action;
      } else if (this.locationText.current === this.locationText.action) {
        this.locationText.current = this.locationText.updating;
      } else {
        this.locationText.current = this.locationText.action;
      }
    },

    /**
     * Once the search text field is updated a new search request is
     * sent to the API. So if we want to update search results we
     * must update the search text that is in our state.
     */
    triggerSearch() {
      this.$store.commit('UPDATE_KEYWORD', 'Current Location');
    },

    /**
     * Update the current location text to notify to the user
     * what is taking place. Send the request to our state
     * to set the current location.
     *
     * Once we have the current location available in our state
     * we will trigger the search.
     */
    setCurrentLocationUpdateText() {
      this.toggleLocationText();
      this.$store.dispatch('setCurrentLocation').then(() => {
        this.toggleLocationText();
        this.triggerSearch();
      });
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