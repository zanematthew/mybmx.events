<template>
  <a :class="added ? 'is-active' : ''" v-on:click.stop.prevent="toggle" class="star add-to-library">
    <icon :name="added ? 'star' : 'star-o'"></icon>
  </a>
</template>
<script>
export default {
  props: {
    item_id: {
      type: Number,
      required: true
    },
    item_type: {
      type: String,
      required: true
    }
  },
  computed: {
    added() {
      return this.$store.getters.isItemInLibrary(this.item_id, this.item_type);
    }
  },
  methods: {
    toggle() {
      this.$store.dispatch('toggleLibraryItem', {
        item_id: this.item_id,
        item_type: this.item_type
      });
    }
  }
}
</script>