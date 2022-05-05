<template>
  <div
    v-show="isDriver"
    id="scroll-to-top"
    @click="onSuggest"
    class="fixed cursor-pointer hover:opacity-70 flex items-center justify-center bottom-20 w-10 h-10 z-10 right-10 bg-red-600 rounded-full drop-shadow-container"
  >
    <span class="material-icons text-white">pan_tool</span>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  data() {
    return {
      isShow: false,
      suggestTrucks: [],
      suggestTruck: {}
    }
  },
  computed: {
    profile() {
      return JSON.parse(localStorage.getItem('profileClient')) || this.$store.state.clientAuth.profile
    },
    isLoggedIn() {
      return this.$store.state.clientAuth.token || localStorage.getItem('tokenClient')
    },
    isDriver() {
      return this.isLoggedIn && this.profile.customer_type == 0
    }
  },
  methods: {
    ...mapActions('driver', {
      getListSuggestTruck: 'getListSuggestTruck',
      getSuggestTruck: 'getSuggestTruck',
      acceptSuggestTruck: 'acceptSuggestTruck'
    }),
    async onSuggest() {
      //   await this.getListSuggestTruck()
    }
  }
}
</script>
