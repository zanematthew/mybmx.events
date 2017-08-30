<template>
  <div class="login-logout">
      <a v-if="isLoggedIn" v-on:click="logout()">Logout</a>
      <a v-else href="/login">Login</a>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import User from '~/models/User';

export default {
  computed: {
    ...mapGetters([
      'isLoggedIn',
    ]),
    authUser() {
      return authuser;
    }
  },
  methods: {
    logout() {
      User.logout(response => {
        window.location.reload();
      });
    }
  },
  mounted() {

    this.$store.commit('SET_AUTHUSER', this.authUser);

    if (this.isLoggedIn) {
      this.$store.dispatch('fetchAllSchedules');
      this.$store.dispatch('fetchAllScheduledEvents');
      this.$store.dispatch('fetchAllScheduledEventIds');
    }
  }
}
</script>
<style>
.login-logout {
  padding: 5px;
}
</style>