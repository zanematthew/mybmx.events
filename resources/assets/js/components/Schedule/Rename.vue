<template>
<!--
Using one-way data-binding
https://vuex.vuejs.org/en/forms.html
https://ypereirareis.github.io/blog/2017/04/25/vuejs-two-way-data-binding-state-management-vuex-strict-mode/
-->
<div>
  <div :class="{editing: schedule == editing }">
    <span v-on:dblclick.stop.prevent="edit(schedule)">{{ schedule.name }}</span>
    <span class="edit">
    <input
      type="text"
      :value="schedule.name"
      v-scheduleFocus="schedule == editing"
      v-on:blur="cancelEdit(schedule)"
      v-on:keyup.enter="doneEdit(schedule, $event.target.value)"
      v-on:keyup.esc="cancelEdit(schedule)"
      />
      <span class="meta">Press Enter to save, ESC to exit</span>
    </span>
  </div>
  <div class="meta">Last updated {{ fromNow(schedule.updated_at) }}</div>
</div>
</template>
<script>
import MyMixin from '../../mixin.js';

export default {
  mixins: [MyMixin],
  props: {
    schedule: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      editing: null
    }
  },
  directives: {
    /**
     * Set the input field to have focus when the user double clicks it.
     *
     * https://medium.com/@nickdenardis/vue-js-return-object-to-previous-state-on-cancel-2fa0f2db700a
     * https://vuejs.org/v2/examples/todomvc.html?
     *
     */
    scheduleFocus(el, binding) {
      if (binding.value) {
        el.focus();
      }
    }
  },
  methods: {
    edit(schedule) {
      this.editing = schedule;
    },
    doneEdit(schedule, name) {
      this.$store.dispatch('rename', {
        id: schedule.id,
        name: name
      }).then(response => {
        this.resetEditing();
      });
    },
    cancelEdit(schedule) {
      this.resetEditing();
    },
    resetEditing() {
      this.editing = null;
    }
  }
}
</script>
<style lang="scss">
.edit {
  display: none;
}

.editing {
  position: relative;
  .edit {
    display: block;
    position: absolute;
    top: 0;
  }
}
</style>