<template>
  <div>
    <schedule-add :schedules="schedules.data"></schedule-add>
    <div v-for="schedule in schedules.data" class="content row is-item">
      <div class="grid is-80">
        <div :class="{editing: schedule == editing }">
          <span v-on:dblclick="edit(schedule)">{{ schedule.name }}</span>
          <span class="edit">
          <input
            type="text"
            v-model="schedule.name"
            v-scheduleFocus="schedule == editing"
            v-on:blur="cancelEdit(schedule)"
            v-on:keyup.enter="doneEdit(schedule)"
            v-on:keyup.esc="cancelEdit(schedule)"
            />
            <span class="meta">Press Enter to save, ESC to exit</span>
          </span>
        </div>
        <div class="meta">
          Created {{ fromNow(schedule.created_at) }} | Events (N/A)
        </div>
      </div>
      <schedule-delete :id="schedule.id" :schedules="schedules.data"></schedule-delete>
    </div>
  </div>
</template>
<script>
import ScheduleAdd from '../components/ScheduleAdd';
import ScheduleDelete from '../components/ScheduleDelete';
import MyMixin from '../mixin';

export default {
  mixins: [MyMixin],
  components: {
    'schedule-add': ScheduleAdd,
    'schedule-delete': ScheduleDelete
  },
  data() {
    return {
      schedules: [],
      _scheduleOriginal: {},
      editing: null
    }
  },
  mounted() {
    this.getScheduels();
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
    getScheduels() {
      axios.get('/api/schedules/').then(response => {
        this.schedules = response.data;
      }).catch(response => {
        console.log(error);
      });
    },
    edit(schedule) {
      this._scheduleOriginal = Object.assign({}, schedule);
      this.editing = schedule;
    },
    doneEdit(schedule) {
      // Send AJAX request
      // Update model name
      axios.post(`/api/schedules/${schedule.id}/edit/`, {
        id: schedule.id,
        name: schedule.name
      }).then(response => {
        // Toggle the input field
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