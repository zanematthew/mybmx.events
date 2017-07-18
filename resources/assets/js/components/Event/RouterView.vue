<template>
  <router-view></router-view>
</template>
<script>
export default {
  metaInfo() {
    return {
      // title: this.getMonthName(),
      title: 'Foo',
      titleTemplate: '%s | My BMX Events'
    }
  }
}
</script>
