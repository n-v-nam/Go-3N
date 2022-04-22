<!-- @format -->

<template>
  <div class="sidebar-container fixed shadow-2xl border-r-2 h-screen w-60 bg-gray-100">
    <div class="flex justify-center items-center">
      <img class="w-2/5 ml-4 mb-4 border-b-4 border-red-200" src="@/assets/img/logo.svg" alt="Logo" />
    </div>
    <div class="items" v-for="(item, index) in items" :key="index">
      <ItemSideBar @selectItemSidebar="selectItemSidebar" :item="item" :itemSelected="itemSelected" />
    </div>
  </div>
</template>

<script>
import ItemSideBar from './ItemSidebar.vue'
import _items from './sidebar-items'
export default {
  name: 'Sidebar',
  data() {
    return {
      itemSelected: null,
      items: _items
    }
  },
  components: {
    ItemSideBar
  },
  methods: {
    selectItemSidebar(item) {
      this.itemSelected = item
      this.$router.push(item.slug)
    }
  },
  created() {
    this.itemSelected = this.items.find((item) => item.slug === this.$route.name)
    if (!this.itemSelected) this.itemSelected = { slug: this.$route.name }
  }
}
</script>

<style lang="scss" scoped>
.sidebar-container {
  box-sizing: border-box;
}
</style>
