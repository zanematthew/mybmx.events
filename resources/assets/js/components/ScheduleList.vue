<template>
  <div>
    <div class="">
      <input type="text" placeholder="Schedule Name..." />
      <input type="button" value="Add Schedule" />
    </div>
    <div class="content row is-item is-condensed">
      Item
    </div>
  </div>
</template>
<script>
export default {
    mounted() {
        axios.get('/api/user/', {
            validateStatus: function (status) {
                if (status == 401) {
                    console.log('Please login');
                }
                return status;
            }
         });
    }
}
</script>