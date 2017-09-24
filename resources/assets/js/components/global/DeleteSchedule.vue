<template>
  <div>
    <a href="#" v-on:click.stop.prevent="destroy(id)" class="delete"><icon name="trash-o" class="align-icon"></icon>Delete</a>
  </div>
</template>
<script>
export default {
  props: ['id'],
  computed: {
    name() {
      return this.$route.query.name || 'Name unavailable';
    }
  },
  methods: {
    destroy(schedule) {
      if (window.confirm(`Delete schedule: "${this.name}". Are you sure?`)) {
        this.$store.dispatch('delete', {
          id: this.id
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