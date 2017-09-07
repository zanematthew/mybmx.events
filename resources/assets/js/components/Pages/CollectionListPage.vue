<template>
  <div>

    <router-link v-if="showMenu !== true" :to="{ name: 'collections'}" class="grid row is-item" v-on:click.native="toggleMenu" exact><icon name="chevron-left"></icon></router-link>

    <div v-if="showMenu">
      <router-link :to="{ name: 'collection-type', params: { item_type: 'event' } }" v-on:click.native="toggleMenu" class="grid is-100 row is-item title">Events</router-link>
      <!-- <router-link :to="{ name: 'collection-type', params: { item_type: 'schedule' } }" v-on:click.native="toggleMenu" class="grid is-100 row is-item title">Schedules</router-link> -->
      <router-link :to="{ name: 'collection-type', params: { item_type: 'venue' } }" v-on:click.native="toggleMenu" class="grid is-100 row is-item title">Venues</router-link>
    </div>

    <router-view></router-view>
  </div>
</template>
<script>
export default {
  props: {},
  data() {
    return {
      // @todo The entire show/hide/toggle menu
      // UI/UX seems "hackish".
      showMenu: true
    }
  },
  computed: {
    routeName() {
      return this.$route.name;
    }
  },
  methods: {
    toggleMenu() {
      this.showMenu = !this.showMenu;
    }
  }
}
</script>
<style lang="scss">
@import "../../../sass/variables";
</style>