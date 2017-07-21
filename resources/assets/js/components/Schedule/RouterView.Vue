<template>
<div>
  <secondary-nav :items="items"></secondary-nav>
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
