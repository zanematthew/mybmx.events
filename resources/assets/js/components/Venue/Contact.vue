<template>
  <div itemscope itemtype="http://schema.org/LocalBusiness">
    <ul class="icon-menu">
      <li v-if="venue.phone_number"><icon name="phone" class="align-icon"></icon> <span itemprop="telephone">{{ venue.phone_number }}</span></li>
      <li><icon name="envelope-o" class="align-icon"></icon> <span itemprop="email">{{ venue.email }}</span></li>
      <li><icon name="user" class="align-icon"></icon> <span itemprop="telephone">{{ venue.primary_contact }}</span></li>
      <li v-if="venue.website"><icon name="globe" class="align-icon"></icon> <a :href="venue.website" target="new" itemprop="url">{{ venue.website }}</a></li>
    </ul>
  </div>
</template>
<script>
export default {
  name: 'venue-detail',
  props: ['venue']
}
</script>
<style lang="scss" scoped>
.address {
  .title {
    margin-bottom: 10px;
  }
}
.icon-menu {
  li {
    list-style-type: none;
    padding: 5px 0;
  }
}
</style>