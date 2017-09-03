<template>
  <form v-on:submit.stop.prevent="addSchedule" class="form">
    <input type="text" placeholder="Schedule Name..." v-model="name" required class="name" />
    <input type="submit" value="Add Schedule" v-bind:disabled="name == ''" class="inline-button" />
  </form>
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
<style lang="scss" scoped>
.form {
  .name {
    width: 65%;
    margin-right: 10px;
  }
  .inline-button {
    width: 30%;
  }
}
</style>