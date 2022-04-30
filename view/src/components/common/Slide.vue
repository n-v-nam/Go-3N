<template>
  <div class="slide border-2 relative" :class="{ 'h-96': !images.length }">
    <span
      v-show="previousable"
      class="material-icons hover:text-red-600 cursor-pointer text-4xl absolute left-0 top-1/2"
      @click="previous"
    >
      keyboard_double_arrow_left
    </span>
    <span
      v-show="nextable"
      class="material-icons hover:text-red-600 cursor-pointer text-4xl absolute right-0 top-1/2"
      @click="next"
    >
      keyboard_double_arrow_right
    </span>
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
  computed: {
    nextable() {
      return this.index < this.images.length - 1
    },
    previousable() {
      return this.index > 0
    }
  },
  methods: {
    next() {
      const next = ++this.index
      this.index = next % this.images.length
    },
    previous() {
      const previous = --this.index < 0 ? this.images.length - 1 : this.index
      this.index = previous % this.images.length
    }
  }
}
</script>
