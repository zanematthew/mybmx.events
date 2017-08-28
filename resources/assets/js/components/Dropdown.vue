<template>
<div class="dropdown">
  <div class="dropdown-trigger" v-on:click="toggle(schedule)">
    <icon name="ellipsis-h" scale="2"></icon>
  </div>
  <div class="dropdown-menu"
  v-if="show"
  :tabindex="schedule.id"
  v-on:blur="toggle()"
  v-focus-on-create
  >
    <div class="dropdown-content">
      <div class="dropdown-item" v-on:click="emitRename(schedule)">Rename</div>
      <div class="dropdown-divider"></div>
      <div class="dropdown-item delete">
        <schedule-delete :schedule="schedule"></schedule-delete>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import ScheduleDelete from '~/components/Schedule/Delete';

export default {
  components: {
    'schedule-delete': ScheduleDelete,
  },
  props: ['schedule'],
  data() {
    return {
      show: false
    }
  },
  directives: {
    /**
     * By setting the focus of the newly created menu item, we can
     * take advantage of the native onblur event, which is used
     * as our "click away" event to hide the menu.
     *
     * @todo Need a better solution that works on mobile.
     */
    focusOnCreate(el, binding) {
      Vue.nextTick(() => {
        el.focus();
      });
    }
  },
  methods: {
    toggle(schedule) {
      this.show = !this.show;
    },
    emitRename(schedule) {
      this.$emit('doRename', schedule);
    }
  }
}
</script>
<style lang="scss">
@import "../../sass/variables";
.dropdown {
  position: relative;
  .dropdown-trigger {
    color: $light-gray;
    text-align: right;
    :hover {
      cursor: pointer;
    }
  }
  .dropdown-menu {
    min-width: 200px;
    position: absolute;
    background: #fff;
    right: 0;
    z-index: 1;
  }
  .dropdown-content {
    border: 1px solid $light-gray;
    box-shadow: 1px 1px 4px rgba(136, 136, 136, 0.20);
    border-radius: 2px;
  }
  .dropdown-item {
    padding: 10px 20px;
    cursor: pointer;
    &:hover {
      background: rgba(236, 233, 233, 0.23);
      color: $primary-focus-color;
    }
  }
  .dropdown-divider {
    border-top: 1px solid $light-gray;
  }
}
</style>