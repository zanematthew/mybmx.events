<template>
  <div>
    <!-- Tabs here per each type, hard coded -->
    <search-form></search-form>
    <search-tabs></search-tabs>
  </div>
</template>
<script>
import searchTabs from '~/components/SearchTabs';
import searchForm from '~/components/SearchForm';

export default {
  components: {
    searchTabs,
    searchForm
  }
}
</script>