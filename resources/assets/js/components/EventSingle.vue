<template>
<div id="event-single">
  <div class="content row is-item">
    <div class="event-mini">
      <div class="grid is-100">
        <div class="title">
          <router-link :to="{ name: 'event-single', params: { id: event.id, slug: event.slug } }">{{ event.title }}</router-link>
        </div>
        <div><strong>{{ fromNow(event.start_date) }}</strong>, {{ startEndDate(event.start_date, event.end_date) }}</div>
        <div class="body">
          <div>
            <strong>{{ event.type_name }}</strong> &bull; <strong>{{ event.venue.name }}</strong> &bull; {{ event.venue.city.name }}<span v-if="event.venue.city.states">, {{ event.venue.city.states[0].abbr }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row is-item">
    <div class="content">
      <div class="body">
        <div itemscope itemtype="http://schema.org/LocalBusiness">
          <div class="grid is-20">
            <div class="image">
              <img :src="event.venue.image_uri" itemprop="image" alt="Photo of Jane Joe">
            </div>
          </div>
          <div class="grid is-40">
            <h1 class="title"><span itemprop="name">{{event.venue.name}}</span></h1>
            <span v-if="event.venue.description" itemprop="description"><p>{{event.venue.description}}</p></span>
            <address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
              <span v-if="event.venue.street_address" itemprop="streetAddress">{{event.venue.street_address}}</span><br>
              <span itemprop="addressLocality">{{event.venue.city.name}}</span>,
              <span v-if="event.venue.city.states" itemprop="addressRegion">{{event.venue.city.states[0].abbr}}</span><br>
              <span>{{event.venue.zipCode}}</span>
            </address>
          </div>
          <div class="grid is-40">
            <strong>Phone</strong> <span itemprop="telephone">{{event.venue.phone_number}}</span><br>
            <strong>Email</strong> <span itemprop="email">{{event.venue.email}}</span><br>
            <strong>Primary Contact</strong> <span itemprop="telephone">{{event.venue.primary_contact}}</span><br>
            <strong>Site</strong> <a :href="event.venue.website" target="_blank" itemprop="url">{{event.venue.website}}</a><br>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import moment from 'moment';

export default {
  props: ['id', 'slug'],
  data() {
    return {
      // https://stackoverflow.com/questions/40713905/deeply-nested-data-objects-in-vuejs
      event: { venue: { city: { states: '' } } }
    }
  },
  mounted() {
    var self = this;
    axios.get('/api/event/'+this.id+'/'+this.slug).then(function(response){
      self.event = response.data;
    }).catch(function(response){
      console.log('catch');
    });
  },
  methods: {
    fromNow(start_date) {
      return moment(start_date).fromNow();
    },
    startEndDate(start_date, end_date) {
      var startMonthDate = moment(start_date).format("MMM D"),
          year           = moment(end_date).format("YYYY");

      if (start_date == end_date) {
        return startMonthDate + ", " + year;
      } else {
        var endDate = moment(end_date).format("D");
        return startMonthDate + " \u2013 " + endDate + ", " + year;
      }
    },
  }
}
</script>