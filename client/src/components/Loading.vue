<template>
  <div class="loading">
    <p><i class="fa fa-spinner fa-spin fa-5x fa-fw"/></p>
    <p class="loading-text">
      <slot>Loading...</slot>
    </p>
  </div>
</template>
<script>
export default {
    name:'Loading'
}
</script>
<style lang="scss" scoped>
.loading {
  text-align: center;
  margin: 10px;
  display: flex;
  flex-direction: column;

  .loading-text {
    font-size: 22px;
  }
}
</style>


