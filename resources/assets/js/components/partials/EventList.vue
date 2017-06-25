<template>
  <div>
  <!-- Upcoming Events this month -->
  <div class="row content is-item is-condensed">
    <h2 class="title">{{ title }}</h2>
  </div>
  <div class="content row is-item is-condensed" v-for="event in data">
    <h1><router-link :to="{ name: 'event-single', params: { id: event.id, slug: event.slug }}">{{ event.title }}</router-link> {{ fromNow(event.start_date) }}, {{ startEndDate(event.start_date, event.end_date) }}</h1>
  </div>
</div>
</template>
<script>
import MyMixin from '../../mixin.js';

export default {
  props: ['data', 'title'],
  mixins: [MyMixin],
};
</script>