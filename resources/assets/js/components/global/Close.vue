<template>
  <div class="grid row is-item close-container" v-on:click.prevent="back">
    <icon name="chevron-left"></icon>
  </div>
</template>
<script>
import router from '~/router';
export default {
  methods: {
    back() {
      this.$emit('beforeBack');
      if (this.$route.name == 'venue-single-events') {
        router.push({ name: 'venues' });
      } else if (this.$route.name == 'event-single-page') {
        router.push({ name: 'events' });
      } else if (window.history.length > 2) {
        router.go(-1);
      } else {
      }
    }
  }
}
</script>
<style>
.close-container {
  cursor: pointer;
  opacity: .75;
  background: #fff;
  max-height: 48px;
  min-height: 48px;
}
</style>