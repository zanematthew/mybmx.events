<template>
  <div class="content row is-item">
    <form v-on:submit.prevent="addSchedule" class="form">
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
  props: ['schedules'],
  data() {
    return {
      name: [],
      submit: false
    }
  },
  methods: {
    clearField() {
      return this.name = '';
    },
    addSchedule: _.throttle(function(){
      axios.post('/api/schedules/new', {
        name: this.name
      }).then(response => {
        this.clearField();
        console.log(response);
        this.schedules.unshift({
          id: response.data.id,
          name: response.data.name,
          created_at: response.data.create_at
        });
      });
    }, 2000)
  }
}
</script>
