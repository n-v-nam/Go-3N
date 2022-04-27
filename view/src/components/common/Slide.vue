<template>
  <div class="slide border-2 relative">
    <span class="material-icons hover:text-red-600 cursor-pointer text-4xl absolute left-0 top-1/2" @click="previous">
      keyboard_double_arrow_left
    </span>
    <span class="material-icons hover:text-red-600 cursor-pointer text-4xl absolute right-0 top-1/2" @click="next">keyboard_double_arrow_right</span>
    <img class="w-full px-20" :src="images[index]" />
  </div>
</template>

<script>
export default {
  props: {
    images: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      index: 0
    }
  },
  methods: {
    next() {
      const next = ++this.index
      this.index = next % this.images.length
    },
    previous() {
      const previous = --this.index
      this.index = previous % this.images.length
    }
  }
}
</script>
