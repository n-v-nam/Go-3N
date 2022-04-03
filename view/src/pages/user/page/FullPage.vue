<template>
  <div class="full-page">
    <div class="header-page relative text-center text-white py-14 font-bold text-2xl md:text-4xl">
      {{ title }}
      <div class="overlay"></div>
    </div>
    <div class="breadcrumb bg-gray-200 py-2 px-2 md:px-10 xl:px-28">
      <span v-for="(item, index) in breadcrums" :key="index">
        <span class="mark text-xs text-gray-500 mx-1">
          {{ index ? '&#9658;' : '' }}
        </span>
        <span
          class="text-gray-600 cursor-pointer hover:text-red-600"
          :class="{ 'text-red-600 pointer-events-none': item.path == $route.path }"
          @click="$router.push(item.path)"
        >
          {{ item.name }}</span
        >
      </span>
    </div>
    <router-view class="mx-2 md:mx-10 xl:mx-28"></router-view>
  </div>
</template>

<script>
export default {
  data() {
    return {}
  },
  computed: {
    title() {
      return this.$route.meta.title
    },
    breadcrums() {
      const matched = [...this.$route.matched]
      matched.shift()
      const items = matched.map((parentRoute) => {
        const item = {
          name: parentRoute.name,
          path: parentRoute.path
        }
        return item
      })
      return items
    }
  }
}
</script>
<style lang="scss" scoped>
.header-page {
  background-image: url('../../../assets/img/user/bg-login.png');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: center -250px;
  background-size: cover;
  z-index: 1;
  height: 150px;
}
</style>
