<template>
  <div class="login-logout">
      <a v-if="isLoggedIn" v-on:click="logout()">Logout</a>
      <a v-else href="/login">Login</a>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
// @todo move to vuex
import User from '~/api/User';

export default {
  computed: {
    ...mapGetters([
      'isLoggedIn',
    ])
  },
  methods: {
    logout() {
      User.logout(response => {
        window.location.reload();
      });
    }
  }
}
</script>
<style>
.login-logout {
  padding: 5px;
}
</style>