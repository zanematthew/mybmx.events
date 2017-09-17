<template>
  <div v-if="items">
    <action-bar :type="item_type" :item="item" :key="item.id" v-for="item in items" class="row"></action-bar>
  </div>
  <div v-else class="align-center row is-item grid is-100">
    <icon name="refresh" spin></icon>
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
      return this.$store.state.library[this.item_type];
    }
  }
}
</script>