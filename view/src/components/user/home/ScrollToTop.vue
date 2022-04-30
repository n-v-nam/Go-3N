<template>
  <div
    v-show="scrollTopEnabled"
    id="scroll-to-top"
    @click="onScrollToTop"
    class="fixed cursor-pointer hover:opacity-70 flex items-center justify-center bottom-5 w-10 h-10 z-10 right-10 bg-red-600 rounded-full drop-shadow-container"
  >
    <span class="material-icons text-white">north</span>
  </div>
</template>

<script>
export default {
  computed: {
    scrollTopEnabled() {
      return this.$store.state.app.scroll.scrollY > 100
    }
  },
  methods: {
    onScrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }
  }
}
</script>
