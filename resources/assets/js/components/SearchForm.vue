<template>
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
</template>
<script>
import { mapState } from 'vuex';

export default {
  computed: {
    keyword: {
      get () {
        return this.$store.state.search.keyword;
      },
      set (value) {
        this.$store.commit('UPDATE_KEYWORD', value);
      }
    },
    typeDelay() {
      return this.$store.state.search.typeDelay;
    }
  },
  methods: {
    search: _.debounce(function(){
      this.$store.dispatch('getSearchResults');
    }, this.typeDelay)
  },
  watch: {
    keyword: function () {
      this.search();
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
</style>