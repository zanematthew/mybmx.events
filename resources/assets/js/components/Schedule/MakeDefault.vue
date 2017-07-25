<template>
<div class="star" v-on:click="makeDefault(schedule)">
  <icon :name="schedule.default ? 'star': 'star-o'"></icon>
</div>
</template>
<script>
export default {
  props: ['initialSchedule'],
  data() {
    return {
      schedule: this.initialSchedule
    }
  },
  methods: {
    makeDefault(schedule) {
      axios.post(`/api/user/schedule/${schedule.id}/toggle-default/`, {
        id: schedule.id
      }).then(response => {
        this.schedule = response.data;
      }).catch(error => {
        console.log(error);
      });
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