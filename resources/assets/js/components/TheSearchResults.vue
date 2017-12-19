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
      v-model="currentText"
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
    v-if="currentText === ''"
    class="link grid row is-item"
    v-on:click.prevent="setCurrentLocationUpdateText">
    <icon name="location-arrow"></icon> {{ locationText.current }}
  </div>

  <!-- Has result state -->
  <!-- When we have results display the action-bar -->
  <!-- for each item in our result set. -->
  <div
    v-if="resultsCount > 0"
    :type="type"
    :item="result"
    :key="result.id"
    v-for="result in allCurrentResults[type]"
    class="row is-item grid is-100">
      <div v-if="type === 'event'">
        <router-link v-if="type === 'event'"
          :to="{
            name: 'event-single-page',
            params: { id: result.id, slug: result.slug, when: 'this-month' },
            query: { venue_id: result.venue_id }
          }"
          exact>
          <div class="title">{{ result.title }} at {{ result.venue_name }}</div>
          <div class="not-title">
            {{ fromNow(result.registration) }} &bull;
            {{ formatTime(result.registration) }}
            <div>{{ result.city }}, {{ result.state }}
            <span v-if="result.distance_from">&bull; Distance {{ result.distance_from }}miles</span></div>
          </div>
        </router-link>
      </div>
      <div v-else-if="type === 'venue'">
        <router-link v-if="type === 'venue'"
          :to="{
          name: 'venue-single-events',
          params: { venue_id: result.id, slug: result.slug, when: 'this-month' }
          }" class="title-click-area title" exact>

          <div class="avatar">
            <img :src="result.image_uri" itemprop="image" alt="Photo of Jane Joe">
          </div>

          <div class="title">{{ result.name }}</div>
          <div class="not-title">
            {{ result.city }}, {{ result.state }}
            <span v-if="result.distance_from">
              &bull; Distance {{ result.distance_from }}miles
            </span>
          </div>
        </router-link>
      </div>
      <div v-else>
        Type: {{ type }} not yet supported.
      </div>
    </div>

  <!-- Has no result state -->
  <!-- If we have text, and no results are found -->
  <!-- then display a message. -->
  <div
    v-if="didSearch && doingSearch === false"
    class="grid row is-item">
    No results
  </div>

</div>
</template>
<script>
import { mapState } from 'vuex';
import MyMixin from '~/mixin.js';
import actionBar from '~/components/ActionBar';

export default {
  mixins: [MyMixin],
  components: {
    actionBar,
  },
  computed: {
    ...mapState({
      searchText:        state => state.search.text.current,
      type:              state => state.route.params.type,
      allCurrentResults: state => state.search.results
    }),
    currentText: {
      get() {
        return this.searchText;
      },
      set(value) {
        this.resultsCount = 1;
        if (_.trim(value).length === 0) {
          this.resetSearch();
          return;
        }
        this.$store.commit('UPDATE_KEYWORD', value);
      }
    },
    didSearch: function () {
      return this.currentText && this.resultsCount === 0 ? true : false;
    },
    doingSearch: function () {
      return this.currenText && this.resultsCount === 1 ? true : false;
    }
  },
  data() {
    return {
      // @todo places will be for state, city, zip
      // @todo move count to be part of state.
      // Restructure results state as;
      // search.results[type] = [ count => 0, items = [] ];
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
      resultsCount: 0, // @todo hacky, notes to come.
      locationText: {
        current: '',
        action: 'Current location',
        updating: 'Getting current location...'
      }
    }
  },
  mounted() {
    this.toggleLocationText();
    this.updateSearchType();
  },
  watch: {
    '$route' (to, from) {
      this.updateSearchType();
    },
    currentText: function () {
      this.search();
    }
  },
  methods: {
    search: _.debounce(function() {
      if (_.isEmpty(this.currentText)) {
        return;
      }
      this.$store.commit('CLEAR_SEARCH_RESULTS');
      this.$store.dispatch('getSearchResults').then(response => {
        if (this.allCurrentResults[this.type]) {
          this.resultsCount = this.allCurrentResults[this.type].length;
        }
      });
    }, 250),

    /**
     * Simply put, our search type (sort of) maps to a model in the API.
     *
     * Note; "places", is not a model, its just a flag to narrow search
     * results based on a place, i.e., city, state.
     */
    updateSearchType() {
      this.$store.commit('UPDATE_SEARCH_TYPE', {
        type: this.$store.state.route.params.type
      });
      this.resetSearch();
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
        this.$store.commit('UPDATE_KEYWORD', 'Current Location');
      }).then(() => {
        this.search();
      });
    },

    resetSearch() {
      this.$store.commit('CLEAR_KEYWORD');
      this.$store.commit('CLEAR_SEARCH_RESULTS');
      this.$store.commit('UPDATE_POSITION', {});
      this.resultsCount = 0;
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
.link {
  color: $primary-focus-color;
  .fa-icon {
    margin-right: 10px;
    color: $primary-focus-color;
  }
}
.avatar {
  float: left;
  margin-right: 20px;
}
</style>