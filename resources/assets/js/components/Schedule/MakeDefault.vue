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
  computed: {
    schedules() {
      return this.$store.state.schedule.schedules;
    }
  },
  methods: {
    makeDefault(schedule) {
      this.$store.dispatch('toggleDefault', {
        id: schedule.id
      });
    },
    isDefault(schedule) {
      var foundIndex = this.schedules.findIndex(items => items.id == schedule.id);
      return this.schedules[foundIndex].default;
    }
  }
}
</script>
<style scoped>
.star {
  float: left;
  margin: 10px;
  color: #ffd800;
}
</style>