<template>
  <div class="row nav is-tertiary meta">
    <span v-if="data.last_page > 1">
      <router-link :to="{ name: name, query: { page: this.nextPrevPage( data.prev_page_url ) } }" class="nav-item" exact>
        <icon name="angle-left"></icon>
      </router-link>
      <span class="nav-item">{{ data.current_page }}/{{ data.last_page }}</span>
      <router-link :to="{ name: name, query: { page: this.nextPrevPage( data.next_page_url ) } }" class="nav-item" exact>
        <icon name="angle-right"></icon>
      </router-link>
    </span>
    <div class="nav-item count alight-right">Total {{ data.total }}</div>
  </div>
</template>
<script>
var URL = require('url-parse');

export default {
  props: ['name', 'data'],
  methods: {
    nextPrevPage(url) {
      var parsed = new URL(url, true),
          pageNumber = parsed.query.page;
      return pageNumber || 1;
    }
  }
};
</script>