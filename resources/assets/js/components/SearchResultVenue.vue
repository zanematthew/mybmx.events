<template>
  <div>
    <router-link
      :to="{
      name: 'venue-single-events',
      params: { venue_id: item.id, slug: item.slug, when: 'this-month' }
      }"
      exact>
      <div class="title">{{ item.name }}</div>
      <div class="not-title">{{ item.distance_from }} miles</div>
    </router-link>
  </div>
</template>
<script>
export default {
  props: {
    item: Object
  }
}
</script>
