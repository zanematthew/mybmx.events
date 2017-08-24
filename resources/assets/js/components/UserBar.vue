<template>
  <div>
    Logged in: {{ isLoggedIn }}
    Name: {{ name }}
    <a v-on:click="logout()">Logout</a>
    <a v-if="isLoggedIn" v-on:click="logout()">Logout</a>
    <a v-else href="/redirect/facebook">Login with Facebook</a>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import User from '../models/User';

export default {
  computed: {
    ...mapGetters([
      'name',
      'isLoggedIn'
    ])
  },
  methods: {
    logout() {
      User.logout(response => {
        console.log(response);
      });
    }
  },
  mounted() {
    this.$store.dispatch('fetchProfile');
    this.$store.dispatch('fetchAllSchedules');
    this.$store.dispatch('fetchAllScheduledEvents');
    this.$store.dispatch('fetchAllScheduledEventIds');
  }
}
</script>