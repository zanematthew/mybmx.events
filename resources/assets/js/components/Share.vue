<template>
  <div class="content">
    <social-sharing :url="url" inline-template>
      <div>
        <network network="facebook" class="grid row is-item">
          <icon name="facebook-square" class="align-icon fb"></icon> <a href="#">Facebook</a>
        </network>
        <network network="twitter" class="grid row is-item">
          <icon name="twitter-square" class="align-icon twitter"></icon> <a href="#">Twitter</a>
        </network>
      </div>
    </social-sharing>
  </div>
</template>
<script>
import close from './../components/Close';

export default {
  components: {
    close
  },
  data() {
    return {
      id: this.$route.params.id,
      item: {}
    }
  },
  computed: {
    url() {
      return `${window.location.origin}/${this.id}/${this.item.slug}`;
    }
  },
  mounted() {
    axios.get(`/api/event/${this.$route.params.id}/`).then(response => {
      this.item = response.data
    });
  }
}
</script>
<style lang="scss">
.fb {
  color: #3b5998;
  background: #fff;
}
.twitter {
  color: #1da1f2;
  background: #fff;
}
.align-icon {
  margin-bottom: -2px;
  margin-right: 10px;
}
</style>