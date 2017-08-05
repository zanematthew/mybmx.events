<template>
<div class="star" v-on:click.stop.prevent="makeDefault(schedule)">
  <icon :name="isDefault(schedule) ? 'star': 'star-o'"></icon>
</div>
</template>
<script>
export default {
  props: {
    schedule: {
      type: Object,
      required: true
    }
  },
  methods: {
    makeDefault(schedule) {
      this.$store.dispatch('toggleDefault', {
        id: schedule.id
      });
    },
    isDefault(schedule) {
      var foundIndex = this.$store.state.user.schedule.schedules.findIndex(items => items.id == schedule.id);
      return this.$store.state.user.schedule.schedules[foundIndex].default;
    }
  }
}
</script>
<style lang="scss">
.star {
  float: left;
  margin: 10px;
  color: #ffd800;
}
.main {
  float: left;
}
</style>