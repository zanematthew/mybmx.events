<template>
  <div>
    <p>
      <a v-if="isLoggedIn" v-on:click="logout()">Logout</a>
      <a v-else href="/login">Login</a>
    </p>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import User from '~/models/User';

export default {
  computed: {
    ...mapGetters([
      'name',
      'isLoggedIn'
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

    this.$store.commit('SET_PROFILE', this.authUser);
    this.isLoggedIn;

    if (this.isLoggedIn) {
      this.$store.dispatch('fetchProfile');
      this.$store.dispatch('fetchAllSchedules');
      this.$store.dispatch('fetchAllScheduledEvents');
      this.$store.dispatch('fetchAllScheduledEventIds');
    } else {
      console.log('Not logged in.');
    }
  }
}
</script>