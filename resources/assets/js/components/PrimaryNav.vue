<template>
<div class="nav is-underlined is-primary is-active">
  <span v-for="item in items">
    <router-link :to="{
      name: item.id,
      params: item.params,
    }" class="nav-item">{{ item.name }}</router-link>
  </span>
</div>
</template>
<script>
import MyMixin from '../mixin.js';

export default {
  mixins: [MyMixin],
  props: ['items']
}
</script>