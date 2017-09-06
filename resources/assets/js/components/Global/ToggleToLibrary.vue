<template>
  <a :class="added ? 'is-active' : ''" v-on:click.stop.prevent="toggle" class="add-to-library">
    <icon scale="2" :name="added ? 'check-circle' : 'plus-circle'"></icon>
  </a>
</template>
<script>
import { mapGetters } from 'vuex';

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
    ...mapGetters([
      'isLoggedIn'
    ]),
    added() {
      return this.$store.getters.isItemInLibrary(this.item_id, this.item_type);
    }
  },
  methods: {
    toggle() {
      if (this.isLoggedIn) {
        this.$store.dispatch('toggleLibraryItem', {
          item_id: this.item_id,
          item_type: this.item_type
        });
      } else {
        window.location.replace(`${window.location.origin}/login/`);
      }
    }
  }
}
</script>