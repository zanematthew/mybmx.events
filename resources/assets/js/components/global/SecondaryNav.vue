<template>
  <!-- Secondary Nav -->
  <div class="row">
    <div class="nav is-underlined is-tertiary is-spacious">
      <span v-for="link in items">
        <router-link :to="{
          name: link.name,
          params: link.params,
          query: appendStateQuery()
        }" class="nav-item">{{ link.title }}</router-link>
      </span>
    </div>
  </div>
  <!-- Secondary Nav -->
</template>
<script>
import MyMixin from '../../mixin.js';
export default {
  mixins: [MyMixin],
  name: 'secondary-nav',
  props: {
    /**
     * An array of items corresponding to the contents of `<router-link`.
     * Note currently `query: <foo>` is hard coded to append
     * the state query.
     *
     * @type {Array}
     */
    items: {
      type: Array
    }
  }
}
</script>