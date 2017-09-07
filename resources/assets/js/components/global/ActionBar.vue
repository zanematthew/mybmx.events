<template>
  <div class="action-bar">

    <!-- Library Area -->
    <toggle-to-library v-if="item.id" :item_id="item.id" :item_type="itemType" class="grid is-15"></toggle-to-library>

    <!-- Image Area -->
    <div class="grid is-15 image-area" v-if="item.image_uri">
      <div class="avatar">
        <img :src="item.image_uri" itemprop="image" alt="Photo of Jane Joe">
      </div>
    </div>

    <!-- Title Area (Event) -->
    <router-link v-if="item.venue" :to="{ name: 'event-single',
      params: { id: item.id, slug: item.slug, when: 'this-month' },
      query: { venue_id: item.venue.id }
      }" class="grid is-70 title-click-area" exact>
        <div class="title">{{ item.title }}</div>
        <div class="not-title">
          {{ startDate(item.start_date) }}<span v-if="item.venue"> &bull; {{ item.venue.city.name }}<span v-if="item.venue.city.states">, {{ item.venue.city.states[0].abbr }}</span></span>
        </div>
    </router-link>

    <!-- Title Area (Venue) -->
    <router-link v-else :to="{ name: 'venue-single-events',
      params: { venue_id: item.id, slug: item.slug, when: 'this-month' }
      }" class="grid is-50 title-click-area title" exact>{{ item.name }}</router-link>

    <!-- Detail Area -->
    <router-link :to="{ name: 'action-main', params: { id: item.id } }" class="align-right grid is-15 detail-click-area"><icon name="ellipsis-h"></icon></router-link>
  </div>

</template>
<script>
import MyMixin from '~/mixin.js';
import toggleToLibrary from '~/components/Global/toggleToLibrary';

export default {
  mixins: [MyMixin],
  props: {
    item: { id: '', image_uri: '' }
  },
  components: {
    toggleToLibrary
  },
  computed: {
    itemType() {
      return this.item.venue ? 'event' : 'venue';
    }
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
.not-title {
  font-size: 12px;
}
</style>