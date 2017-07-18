<template>
<div>
  <div :class="{editing: initialSchedule == editing }">
    <span v-on:dblclick="edit(initialSchedule)">{{ initialSchedule.name }}</span>
    <span class="edit">
    <input
      type="text"
      v-model="initialSchedule.name"
      v-scheduleFocus="initialSchedule == editing"
      v-on:blur="cancelEdit(initialSchedule)"
      v-on:keyup.enter="doneEdit(initialSchedule)"
      v-on:keyup.esc="cancelEdit(initialSchedule)"
      />
      <span class="meta">Press Enter to save, ESC to exit</span>
    </span>
  </div>
  <div class="meta">Last updated {{ fromNow(initialSchedule.updated_at) }}</div>
</div>
</template>
<script>
import MyMixin from '../../mixin.js';

export default {
  mixins: [MyMixin],
  props: ['initialSchedule'],
  data() {
    return {
      _scheduleOriginal: {},
      editing: null
    }
  },
  // https://medium.com/@nickdenardis/vue-js-return-object-to-previous-state-on-cancel-2fa0f2db700a
  // https://vuejs.org/v2/examples/todomvc.html?
  directives: {
    scheduleFocus(el, binding) {
      if (binding.value) {
        el.focus();
      }
    }
  },
  methods: {
    edit(schedule) {
      this._scheduleOriginal = Object.assign({}, schedule);
      this.editing = schedule;
    },
    doneEdit(schedule) {
      // Send AJAX request
      // Update model name
      // Toggle the input field
      axios.post(`/api/schedules/${schedule.id}/edit/`, {
        id: schedule.id,
        name: schedule.name
      }).then(response => {
        this._scheduleOriginal = schedule;
        this.editing = null;
      }).catch(error => {
        console.log(error);
      });
    },
    cancelEdit(schedule) {
      Object.assign(schedule, this._scheduleOriginal);
      this.editing = this._scheduleOriginal = null;
    }
  }
}
</script>
<style>
.edit {display: none; }
.editing {
  position: relative;
}
.editing .edit {
  display: block;
  position: absolute;
  top: 0;
}
</style>