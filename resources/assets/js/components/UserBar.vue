<template>
  <div>
    <p><a href="/redirect/facebook">Login with Facebook</a></p>
  </div>
</template>
<script>
export default {
  computed: {
    profile() {
      return this.$store.state.user.profile;
    }
  }
}
</script>