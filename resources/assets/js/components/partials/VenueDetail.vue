<template>
  <div class="row is-item">
    <div class="content">
      <div class="body">
        <div itemscope itemtype="http://schema.org/LocalBusiness">
          <div class="grid is-20 is-mobile-100">
            <div class="image">
              <img :src="image" itemprop="image" alt="Photo of Jane Joe">
            </div>
          </div>
          <div class="grid is-40 is-mobile-100">
            <h1 class="title"><span itemprop="name">{{name}}</span></h1>
            <!-- <span v-if="description" itemprop="description"><p>{{description}}</p></span> -->
            <address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
              <span v-if="street_address" itemprop="streetAddress">{{street_address}}</span><br>
              <span itemprop="addressLocality">{{city}}</span>,
              <span v-if="state_abbr" itemprop="addressRegion">{{state_abbr}}</span> <span>{{zip_code}}</span>
            </address>
          </div>
          <div class="grid is-40 is-mobile-100">
            <span v-if="phone_number"><strong>Phone</strong> <span itemprop="telephone">{{phone_number}}</span><br></span>
            <strong>Email</strong> <span itemprop="email">{{email}}</span><br>
            <strong>Primary Contact</strong> <span itemprop="telephone">{{primary_contact}}</span><br>
            <strong>Site</strong> <a :href="website" target="_blank" itemprop="url">{{website}}</a><br>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'venue-detail',
  props: [
    'name',
    'id',
    'slug',
    'name',
    'image',
    'description',
    'street_address',
    'city',
    'state_abbr',
    'zip_code',
    'phone_number',
    'email',
    'primary_contact',
    'website',
  ]
}
</script>