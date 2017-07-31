<template>
<div class="grid is-20">
  <button v-on:click.stop.prevent="destroy(schedule)" class="delete">Delete</button>
</div>
</template>
<script>
export default {
  props: ['schedule'],
  methods: {
    destroy(schedule) {
      this.$store.dispatch('delete', {
        id: schedule.id
      });
    }
  }
}
</script>
