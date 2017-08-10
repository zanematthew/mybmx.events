<template>
  <div>
    <div class="grid is-100 close-container" v-on:click.prevent="back">
      <icon name="chevron-left"></icon>
    </div>
  </div>
</template>
<script>
import router from '../router';
export default {
  methods: {
    back() {
      if (window.history.length > 2) {
        router.go(-1);
      } else if (this.$route.name == 'venue-single') {
        router.push({ name: 'venues' });
      } else if (this.$route.name == 'event-single') {
        router.push({ name: 'events' });
      } else {
      }
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../sass/variables";
.close-container {
  position: absolute;
  background: #fff;
  padding: 20px;
  opacity: 0.75;
  cursor: pointer;
}
</style>