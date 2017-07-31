<template>
  <div class="content row is-item">
    <form v-on:submit.stop.prevent="addSchedule" class="form">
      <div class="grid is-80">
        <input type="text" placeholder="Schedule Name..." v-model="name" required />
      </div>
      <div class="grid is-20">
        <input type="submit" value="Add Schedule" v-bind:disabled="name == ''" />
      </div>
    </form>
  </div>
</template>
<script>
export default {
  data() {
    return {
      name: ''
    }
  },
  methods: {
    clearField() {
      return this.name = '';
    },
    addSchedule() {
      this.$store.dispatch('addSchedule', {
        name: this.name
      }).then(response => {
        this.clearField();
      });
    }
  }
}
</script>
