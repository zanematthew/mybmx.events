<template>
  <div>

    <!-- Library Area -->
    <library-item-toggle :item_id="item.id" :item_type="'event'" class="grid is-15"></library-item-toggle>

    <!-- Image Area -->
    <div class="grid is-15" v-if="item.image_uri">
      <div class="avatar">
        <img :src="item.image_uri" itemprop="image">
      </div>
    </div>

    <router-link
      :to="{
      name: 'event-single-page',
      params: { id: item.id, slug: item.slug, when: 'this-month' },
      query: { venue_id: item.venue_id }
      }"
      class="grid is-70">
      <div class="title">{{ item.title }}</div>
      <div class="not-title">{{ item.distance_from }} miles &bull; {{ item.state }} &bull; {{ fromNow(item.registration) }}</div>
    </router-link>
  </div>
</template>
<script>
import MyMixin from '~/mixin.js';
import libraryItemToggle from '~/components/LibraryItemToggle';

export default {
  components: {
    libraryItemToggle
  },
  mixins: [MyMixin],
  props: {
    item: Object
  }
}
</script>
