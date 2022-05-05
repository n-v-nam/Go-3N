<template>
  <div
    v-show="isDriver"
    id="scroll-to-top"
    @click="onSuggest"
    class="fixed cursor-pointer hover:opacity-70 flex items-center justify-center bottom-20 w-10 h-10 z-10 right-10 bg-red-600 rounded-full drop-shadow-container"
  >
    <span class="material-icons text-white">pan_tool</span>

    <vs-popup title="Các chuyến phù hợp với bạn" class="z-50" :active.sync="isShow">
      <div
        class="bg-gray-200 p-2 grid grid-cols-4 gap-5 items-center"
        v-for="(suggestTruck, index) in suggestTrucks"
        :key="index"
      >
        <div class="col-start-1 col-end-4">
          Chuyến xe từ
          <span class="font-bold">{{ suggestTruck.from_city }}</span>
          đến
          <span class="font-bold">
            {{ suggestTruck.to_city }}
          </span>
          <p class="">
            Loại hàng: {{ suggestTruck.item_type }}
            <br />
            Giá mong muốn: {{ suggestTruck.price | toCurrency }}
          </p>
        </div>
        <div class="action flex flex-col gap-2 p-2">
          <vs-button size="small" color="success" icon="send" @click="onAcceptSuggest(suggestTruck.suggest_truck_id)">
            Nhận đơn
          </vs-button>
          <vs-button size="small" icon="visibility" @click="onView(suggestTruck.suggest_truck_id)">Xem đơn</vs-button>
        </div>
      </div>
      <p class="text-center my-10" v-if="!suggestTrucks.length">Hiện tại không có chuyến nào</p>
    </vs-popup>
    <vs-popup title="Thông tin chi tiết" :active.sync="isShowDetail">
      <div class="grid grid-cols-2">
        <p>
          <span class="font-bold">Tên khách hàng:</span>
          {{ suggestTruck.customer_name }}
        </p>
        <p>
          <span class="font-bold ml-10">SĐT:</span>
          {{ suggestTruck.customer_phone }}
        </p>
        <p class="col-span-full">
          <span class="font-bold">Tuyến từ:</span>
          {{ suggestTruck.from_city }}
          <span class="font-bold">đến</span>
          {{ suggestTruck.to_city }}
        </p>
        <p>
          <span class="font-bold">Loại hàng:</span>
          {{ suggestTruck.item_type }}
        </p>
        <p>
          <span class="font-bold ml-10">Số lượng gói hàng</span>
          {{ suggestTruck.count || 0 }}
        </p>
        <p>
          <span class="font-bold">Cân nặng hàng:</span>
          {{ suggestTruck.weight }}
        </p>
        <p>
          <span class="font-bold ml-10">Chiều cao hàng:</span>
          {{ suggestTruck.height }}
        </p>
        <p>
          <span class="font-bold">Chiều dài hàng:</span>
          {{ suggestTruck.length }}
        </p>
        <p>
          <span class="font-bold ml-10">Chiều rộng hàng:</span>
          {{ suggestTruck.width }}
        </p>
        <p class="col-span-full my-4 text-center">
          <span class="font-bold">Giá mong muốn:</span>
          <span class="bg-red-400 text-white font-bold text-xl rounded px-3 py-1 mx-4">
            {{ suggestTruck.price | toCurrency }}
          </span>
        </p>
      </div>
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  data() {
    return {
      isShow: false,
      isShowDetail: false,
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
      const { data } = await this.getListSuggestTruck()
      this.suggestTrucks = data ?? []
      this.isShow = true
    },
    async onAcceptSuggest(id) {
      await this.acceptSuggestTruck(id)
    },
    async onView(id) {
      const { data } = await this.getSuggestTruck(id)
      this.suggestTruck = data
      this.isShowDetail = true
    }
  }
}
</script>
