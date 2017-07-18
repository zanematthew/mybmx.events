<template>
<div>

  <!-- Secondary Nav -->
  <div class="row">
    <div class="nav is-underlined is-tertiary is-spacious">
      <span v-for="link in items">
        <router-link :to="{
          name: link.name,
        }" class="nav-item">{{ link.title }}</router-link>
      </span>
    </div>
  </div>
  <!-- Secondary Nav -->

  <router-view></router-view>

</div>
</template>
<script>

export default {
  data() {
    return {
      items: [
        {
          title: 'Schedules',
          name: 'your-schedules'
        },
        {
          title: 'Events',
          name: 'attending-events'
        }
      ],
    }
  },
  watch: {
    '$route' (to, from) {
      console.log('Fetch data here');
      console.log(to);
    }
  }
}
</script>
