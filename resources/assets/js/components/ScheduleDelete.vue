<template>
<div class="grid is-20">
  <button :id="id" v-on:click.prevent="deleteSchedule(id)" class="delete">Delete</button>
</div>
</template>
<script>
import MyMixin from '../mixin.js';

export default {
  mixins: [MyMixin],
  props: ['schedules', 'id'],
  methods: {
    deleteSchedule: _.throttle(function(id) {
      axios.delete(`/api/schedules/${id}/delete/`).then(response => {
        var borland = 0;
        for (let i of this.schedules) {
          if (i['id'] == id) {
            this.schedules.splice(borland, 1);
          }
          borland++;
        }
      }).catch(error => {});
    }, 1000)
  }
}
</script>
