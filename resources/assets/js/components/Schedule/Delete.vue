<template>
  <div>
    <span v-on:click.stop.prevent="destroy(schedule)" class="delete">Delete</span>
  </div>
</template>
<script>
export default {
  props: ['schedule'],
  methods: {
    destroy(schedule) {
      if (window.confirm(`Delete schedule: ${schedule.name}. Are you sure?`)) {
        this.$store.dispatch('delete', {
          id: schedule.id
        });
      }
    }
  }
}
</script>
<style>
.delete {
  color: #c72500;
  display: block;
  cursor: pointer;
}
</style>