<template>
  <div class="reserves-information">
    <div class="header flex mt-10">
      <vs-icon class="text-2xl mr-2" icon="arrow_right"></vs-icon>
      <p class="font-bold text-2xl">Quản lý đơn đã đặt</p>
    </div>
    <div class="truck-content">
      <vs-table
        noDataText="Chưa có dữ liệu đơn đã đặt hoặc bạn chưa đặt đơn nào"
        v-model="selected"
        class="border-2 border-red-200 mt-4"
        pagination
        max-items="10"
        :data="reserves"
      >
        <template slot="header">
          <div class="flex justify-between items-center m-2 mb-8 w-full">
            <div class="flex items-center justify-end">
              <vs-select label="Lọc theo loại đơn" v-model="orderTypeFilter" class="mb-4 pr-2 w-full">
                <vs-select-item
                  :key="index"
                  :value="item.value"
                  :text="item.label"
                  v-for="(item, index) in orderTypes"
                />
              </vs-select>
              <div class="mt-3 ml-2">
                <vs-button icon="search" @click="onSearch"></vs-button>
              </div>
            </div>
            <div class="header flex items-center float-right">
              <vs-icon class="text-red-600" icon="cancel"></vs-icon>
              : Huỷ đơn
              <!-- <vs-icon class="text-green-600 ml-3" icon="attach_money"></vs-icon>
      : Thanh toán -->
              <vs-icon class="text-green-400 ml-3" icon="assignment_return"></vs-icon>
              : Xác nhận thanh toán
              <vs-icon class="ml-3" icon="visibility"></vs-icon>
              : Xem bài đăng
              <vs-icon class="ml-3 text-green-400" icon="check"></vs-icon>
              : Đánh dấu hoàn thành
            </div>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="order_information_id">STT</vs-th>
          <vs-th sort-key="order_code">Mã đơn đặt</vs-th>
          <vs-th sort-key="item_type">Loại hàng</vs-th>
          <vs-th sort-key="location">Tuyến xe</vs-th>
          <vs-th sort-key="price">Giá mong muốn(VNĐ)</vs-th>
          <vs-th sort-key="status">Trạng thái</vs-th>
          <vs-th></vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="prop" :key="index" v-for="(prop, index) in data">
            <vs-td :data="data[index].order_information_id">
              {{ data[index].order_information_id }}
            </vs-td>
            <vs-td :data="data[index].order_code">
              {{ data[index].order_code }}
            </vs-td>
            <vs-td :data="data[index].item_type">
              {{ data[index].item_type }}
            </vs-td>
            <vs-td :data="data[index].location">
              {{ data[index].location }}
            </vs-td>
            <vs-td :data="data[index].price">
              {{ data[index].price }}
            </vs-td>
            <vs-td :data="data[index].status">
              {{ orderStatusText[data[index].status] }}
            </vs-td>
            <vs-td>
              <span
                v-if="[0, 1].includes(data[index].status)"
                class="material-icons text-red-400 hover:text-black"
                @click="onDelete"
              >
                cancel
              </span>
              <span
                v-if="[1].includes(data[index].status)"
                class="material-icons text-green-600 hover:text-black"
                @click="onAccept"
              >
                assignment_return
              </span>
              <!-- <span
                v-if="[6].includes(data[index].status)"
                class="material-icons text-green-600 hover:text-black"
                @click="onPayment"
              >
                attach_money
              </span> -->
              <span
                v-if="[2, 3, 4, 5, 7, 8].includes(data[index].status)"
                class="material-icons hover:text-gray-400"
                @click="onReview(prop.post_id)"
              >
                visibility
              </span>
              <span
                v-if="[9].includes(data[index].status)"
                class="material-icons text-green-400 hover:text-gray-400"
                @click="onComplete"
              >
                check
              </span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <p class="my-4">
      Báo cáo
      <span class="text-red-600">tài xế</span>
      có dấu hiệu bất thường
      <span class="text-red-600 cursor-pointer font-bold hover:text-red-300" @click="$router.push('/report')">
        tại đây
      </span>
      !
    </p>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { orderStatusText, orderStatus } from '@/constants/reserves'

export default {
  name: 'reserves-information',
  data() {
    return {
      reserves: [],
      selected: {},
      isDelete: false,
      orderStatusText,
      orderStatus,
      orderTypeFilter: 1,
      orderTypes: [
        { label: 'Đơn chưa xác nhận', value: 1 },
        { label: 'Đơn đang giao', value: 2 },
        { label: 'Đơn đã giao', value: 3 },
        { label: 'Đơn đã bị huỷ', value: 4 }
      ]
    }
  },
  methods: {
    ...mapActions('reservation', {
      getReserves: 'getReserves',
      deleteReserve: 'deleteReserve',
      getReserve: 'getReserve',
      acceptReserve: 'acceptReserve',
      completeReserve: 'completeReserve'
    }),
    onDelete() {
      console.log(this.selected)
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Xác nhận xoá ?',
        text: 'Bạn có chắc chắn muốn huỷ đơn này ?',
        accept: this.actionDelete,
        acceptText: 'Xoá',
        cancelText: 'Thoát'
      })
    },
    onAccept() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'success',
        title: 'Xác nhận ?',
        text: 'Bạn có chắc chắn đồng ý cho tài xế đến lấy hàng này ?',
        accept: this.actionAccept,
        acceptText: 'Xác nhận',
        cancelText: 'Thoát'
      })
    },
    onPayment() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'success',
        title: 'Thanh toán đơn hàng ?',
        text: 'Bạn có chắc chắn đồng ý thanh toán cho đơn này này?',
        accept: this.actionPayment,
        acceptText: 'Xác nhận',
        cancelText: 'Thoát'
      })
    },
    onComplete() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'success',
        title: 'Xác nhận đơn hàng ?',
        text: 'Bạn có chắc chắn đánh dấu đơn hàng đã hoàn thành?',
        accept: this.actionComplete,
        acceptText: 'Xác nhận',
        cancelText: 'Thoát'
      })
    },
    onReview(postId) {
      this.$router.push(`/post/view/${postId}`)
    },
    clearEvent() {
      this.isDelete = false
    },
    async actionDelete() {
      await this.deleteReserve(this.selected.order_information_id)
      await this.onSearch()
      this.clearEvent()
    },
    async actionAccept() {
      await this.acceptReserve(this.selected.order_information_id)
      await this.onSearch()
      this.clearEvent()
    },
    async actionComplete() {
      await this.completeReserve(this.selected.order_information_id)
      await this.onSearch()
      this.clearEvent()
    },
    async actionPayment() {
      await this.onSearch()
      this.clearEvent()
    },
    async onSearch() {
      const { data } = await this.getReserves(this.orderTypeFilter)
      this.reserves = [...data].map(reserves => {
        const { from_city, to_city } = reserves
        const location = `${from_city} - ${to_city}`
        return { ...reserves, location }
      })
    }
  },
  async created() {
    await this.onSearch()
  }
}
</script>
