<template>
  <div>
    <action-bar v-if="items" :item="item" :key="item.id" v-for="item in items.data" class="row"></action-bar>
  </div>
</template>
<script>
import actionBar from '~/components/Global/ActionBar';
import Library from '~/api/Library';

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