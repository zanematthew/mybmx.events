<template>
  <div>
    <router-link
      :to="{
      name: 'event-single-page',
      params: { id: item.id, slug: item.slug, when: 'this-month' },
      query: { venue_id: item.venue_id }
      }"
      exact>
      <div class="title">{{ item.title }}</div>
      <div class="not-title">{{ item.distance_from }} miles &bull; {{ fromNow(item.registration) }}</div>
    </router-link>
  </div>
</template>
<script>
import MyMixin from '~/mixin.js';

export default {
  mixins: [MyMixin],
  props: {
    item: Object
  }
}
</script>
