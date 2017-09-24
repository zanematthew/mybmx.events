<template>
  <div class="action-bar" v-if="item.id">

    <!-- Library Area -->
    <toggle-to-library :item_id="item.id" :item_type="type" class="grid is-15"></toggle-to-library>

    <!-- Image Area -->
    <div class="grid is-15 image-area" v-if="imageUri">
      <div class="avatar">
        <img :src="imageUri" itemprop="image" alt="Photo of Jane Joe">
      </div>
    </div>

    <!-- Title Area (Event) -->
    <router-link v-if="type === 'event'"
      :to="{
      name: 'event-single-page',
      params: { id: item.id, slug: item.slug, when: 'this-month' },
      query: { venue_id: item.venue.id }
      }"
      class="grid is-50 title-click-area" exact>
        <div class="title">{{ item.title }}</div>
        <div class="not-title" v-if="item.start_date">
          {{ startDate(item.start_date) }}<span v-if="item.venue"> &bull; {{ item.venue.city.name }}<span v-if="item.venue.city.states">, {{ item.venue.city.states[0].abbr }}</span></span>
        </div>
    </router-link>

    <!-- Title Area (Venue) -->
    <router-link v-if="type === 'venue'"
      :to="{
      name: 'venue-single-events',
      params: { venue_id: item.id, slug: item.slug, when: 'this-month' }
      }" class="grid is-50 title-click-area title" exact>{{ item.name }}</router-link>

    <!-- Title Area (Schedule) -->
    <router-link v-if="type === 'schedule'" :to="{ name: 'schedule-single-page',
      params: { id: item.id, slug: item.slug }
      }" class="grid is-50 title-click-area title" exact>{{ item.name }}</router-link>

    <!-- Detail Area -->
    <router-link :to="{
      name: 'action-main',
      params: { id: item.id, type: type },
      query: { slug: item.slug, name: item.name, venue_id: item.venue_id }
    }" class="align-right grid is-15 detail-click-area"><icon name="ellipsis-h"></icon></router-link>
  </div>
  <div v-else class="align-center row is-item grid is-100">
    <icon name="refresh" spin></icon>
  </div>

</template>
<script>
import MyMixin from '~/mixin.js';
import toggleToLibrary from '~/components/Global/toggleToLibrary';

export default {
  mixins: [MyMixin],
  props: {
    item: { id: '' },
    type: {
      type: String,
      required: true
    }
  },
  computed: {
    imageUri() {
      if (this.type === 'schedule')
        return;
      return this.item.image_uri || this.item.venue.image_uri;
    }
  },
  components: {
    toggleToLibrary
  }
}
</script>
<style lang="scss">
@import "../../../sass/variables";
.action-bar {
  max-height: 80px;
  min-height: 80px;
  height: 80px;
  .add-to-library {
    line-height: 80px;
    height: 100%;
    text-align: center;
  }
}
.title-click-area {
  height: 100%;
  padding-top: $padding;
  padding-right: 0;
}
.detail-click-area {
  height: 100%;
  color: #000;
  padding-top: $padding;
}
.image-area {
  padding-top: $padding;
  padding-bottom: $padding;
  text-align: center;
}
</style>