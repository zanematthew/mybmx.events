<template>
  <div class="grid row is-item">
    <!--
    This base form style should be used for;
      - search field
      - action/ add new schedule field
      - schedules/all add new schedule
    -->
  <form class="form icon-inside-field">
    <icon name="search"></icon>
    <input type="text" placeholder="Search" />
  </form>
  </div>
</template>
<script>
export default {
  props: {},
  data() {
    return {}
  },
  computed: {},
  methods: {},
  mounted() {}
}
</script>
<style lang="scss" scoped>
@import "../../sass/variables";
.icon-inside-field {
  position: relative;
  input[type="text"] {
    padding-left: 30px;
  }
  .fa-icon {
    position: absolute;
    top: 30%;
    left: 10px;
  }
}
</style>