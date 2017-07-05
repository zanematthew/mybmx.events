<template>
  <div class="is-40 align-right">
    <label class="typo__label" for="ajax"></label>
    <multiselect
      v-model="selectedStates"
      id="ajax"
      label="name"
      track-by="abbr"
      placeholder="Type to search"
      open-direction="bottom"
      :options="states"
      :multiple="true"
      :searchable="true"
      :loading="isLoading"
      :close-on-select="false"
      :limit="4"
      :show-no-results="false"
      @select="appendToStateQueryAndPush"
      @remove="removeFromStateQueryAndPush"
    >
      <template slot="clear" scope="props">
        <div class="multiselect__clear" v-if="selectedStates.length" @mousedown.prevent.stop="clearAll(props.search)"></div>
      </template><span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
    </multiselect>
  </div>
</template>
<script>
import Multiselect from 'vue-multiselect';

export default {
  components: {
    Multiselect
  },
  data () {
    return {
      selectedStates: [],
      states: [],
      isLoading: false,
      separtor: ',',
      type: 'events'
    }
  },
  mounted() {
    this.requestStates();
  },
  methods: {
    /**
     * Sends the AJAX request to the States end-point, sets the
     * needed data for the component, data;
     *   states
     *   selected states
     *   toggles loading icon for multi-select.
     *
     * @return {void}
     */
    requestStates() {
      this.isLoading = true;
      axios.get('/api/states').then(response => {
        this.states    = response.data;
        this.isLoading = false;
        if (this.$route.query.states !== undefined && this.$route.query.states.length != 0) {
          this.selectedStates = this.keyIntersect(this.$route.query.states.split(this.separtor), this.states, 'abbr');
        }
      });
    },
    /**
     * Clear the selected states.
     *
     * @return {void}
     */
    clearAl() {
      this.selectedStates = [];
    },
    /**
     * Find the intersection of two arrays based on a key.
     *
     * @param  {array}  needles  The array to search for.
     * @param  {array}  haystack The array to search in.
     * @param  {string} key      A key to compare.
     *
     * @return {array}           An array containing found items.
     */
    keyIntersect(needles, haystack, key) {
      let found = [];
      for (let i of haystack) {
        if (needles.includes(i[key])) {
          found.push(i);
        }
      }
      return found;
    },
    /**
     * Append state to query string and push new entry into history.
     *
     * @param  {object} selectedOption The selected object containing properties, abbr, name.
     * @param  {string} id             The ID set in the <multiselect> attribute "id".
     *
     * @return {void}
     */
    appendToStateQueryAndPush(selectedOption, id) {
      var currentStates = this.getCurrentStates() || null;
      if (currentStates === null) {
        var states = selectedOption.abbr;
      } else {
        currentStates.push(selectedOption.abbr);
        var states = currentStates.join(this.separtor);
      }
      this.$router.push({ name: this.type, query: { states: states } });
    },
    /**
     * Get the query string of states.
     *
     * @return {array} An array of states.
     */
    getCurrentStates() {
      if (this.$route.query.states === undefined) {
        return;
      }
      return this.$route.query.states.split(this.separtor);
    },
    /**
     * Remove an item from the state query and push a new router state.
     *
     * @param  {object} removedOption Object containing properties; name, abbr.
     * @param  {string} id            The ID set in the <multiselect> attribute "id".
     * @return {void}
     */
    removeFromStateQueryAndPush(removedOption, id) {
      var currentStates = this.getCurrentStates() || null;
      if (currentStates === null) {
        var states = removedOption.abbr;
      } else {
        i = currentStates.indexOf(removedOption.abbr);
        if (i != -1) {
          currentStates.splice(i, 1);
          var states = currentStates.join(this.separtor);
        }
      }
      this.$router.push({ name: this.type, query: { states: states } });
    }
  }
}
</script>