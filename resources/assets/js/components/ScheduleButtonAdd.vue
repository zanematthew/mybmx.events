<template>
  <form v-on:submit.stop.prevent="addSchedule" class="form row">
    <div class="grid is-85 no-padding">
      <input
      type="text"
      placeholder="Schedule Name..."
      v-model="name"
      required
      class="name" />
    </div>

    <div class="grid is-15 temp">
      <button type="submit" v-bind:disabled="name == ''" ><icon name="plus"></icon></button>
    </div>
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
    border: 0;
    padding: 20px;
  }
  .no-padding {
    padding: 0;
  }
}
.temp {
  text-align: center;
  padding: 10px;
  display: block;
}
</style>