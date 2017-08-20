<template>
  <div class="content">
    <div itemscope itemtype="http://schema.org/LocalBusiness">
      <div class="row is-item">
        <div class="grid is-30" v-if="venue.image_uri">
          <img :src="venue.image_uri" itemprop="image" alt="Photo of Jane Joe">
        </div>
        <div class="grid is-70 address">
          <h1 class="title"><span itemprop="name">{{ venue.name }}</span></h1>
          <!-- <span v-if="description" itemprop="description"><p>{{ description }}</p></span> -->
          <address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
            <span v-if="venue.street_address" itemprop="streetAddress">{{ venue.street_address }}</span><br>
            <span itemprop="addressLocality">{{ venue.city.name }}</span>,
            <span v-if="venue.city.states[0]" itemprop="addressRegion">{{ venue.city.states[0].abbr }}</span> <span>{{ venue.zip_code }}</span>
          </address>
        </div>
      </div>
      <ul class="row is-item grid is-100 icon-menu">
        <li v-if="venue.phone_number"><icon name="phone" class="align-icon"></icon> <span itemprop="telephone">{{ venue.phone_number }}</span></li>
        <li><icon name="envelope-o" class="align-icon"></icon> <span itemprop="email">{{ venue.email }}</span></li>
        <li><icon name="user" class="align-icon"></icon> <span itemprop="telephone">{{ venue.primary_contact }}</span></li>
        <li v-if="venue.website"><icon name="globe" class="align-icon"></icon> <a :href="venue.website" target="new" itemprop="url">{{ venue.website }}</a></li>
      </ul>
    </div>
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