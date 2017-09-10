<template>
  <div>
    <router-link :to="{ name: 'collection-list' }" class="row is-item grid"><icon name="chevron-left"></icon></router-link>
    <action-bar :type="item_type" v-if="items" :item="item" :key="item.id" v-for="item in items.data" class="row"></action-bar>
  </div>
</template>
<script>
import actionBar from '~/components/Global/ActionBar';

export default {
  components: {
    actionBar,
  },
  props: {
    item_type: String
  },
  computed: {
    items() {
      return this.$store.state.library.items[this.item_type];
    }
  },
  mounted() {
    this.$store.dispatch('fetchAllLibraryItems').then(response => {
      this.$store.dispatch('fetchAllLibraryItemsContents', {
        item_type: this.item_type
      });
    });
  }
}
</script>