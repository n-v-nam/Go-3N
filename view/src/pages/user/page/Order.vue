<!-- @format -->

<template>
  <div class="truck-manage my-10">
    <TitlePage title="Quản lý đơn hàng" icon="category" />
    <div class="truck-content">
      <vs-table
        noDataText="Chưa có dữ liệu đơn hàng"
        v-model="selected"
        class="border-2 border-red-200 mt-4"
        pagination
        max-items="10"
        :data="orders"
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
              <vs-icon class="text-red-400 ml-3" icon="report"></vs-icon>
              : Báo cáo
              <vs-icon class="ml-3" icon="visibility"></vs-icon>
              : Xem chi tiết
              <vs-icon class="ml-3 text-green-400" icon="assignment_return"></vs-icon>
              : Xác nhận đơn
              <vs-icon class="ml-3 text-green-400" icon="check"></vs-icon>
              : Đánh dấu hoàn thành
            </div>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="order_information_id">STT</vs-th>
          <vs-th sort-key="order_code">Mã đơn</vs-th>
          <vs-th sort-key="item_type">Loại hàng</vs-th>
          <vs-th sort-key="location">Tuyến</vs-th>
          <vs-th sort-key="price">Giá</vs-th>
          <vs-th sort-key="status">Trạng thái</vs-th>
          <vs-th>Hành động</vs-th>
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
                v-if="![3, 4, 7].includes(prop.status)"
                class="material-icons mr-2"
                @click="onView(prop.order_information_id)"
              >
                visibility
              </span>
              <span
                v-if="[0].includes(prop.status)"
                class="material-icons text-green-400 hover:text-black"
                @click="onAccept"
              >
                assignment_return
              </span>
              <span
                v-if="![2, 7, 9, 8, 10].includes(prop.status)"
                class="material-icons text-red-400 hover:text-black"
                @click="onCancel"
              >
                cancel
              </span>
              <span
                v-if="[8, 9].includes(data[index].status)"
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
    <vs-popup title="Chỉnh sửa xe" :active.sync="isShowDialog" button-close-hidden>
      <OrderInformation :order="order" @clearEvent="clearEvent" @actionDelete="onDelete" />
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { convertToCamelCase } from '@/helpers/convert-keys'
import { orderStatusText } from '@/constants/reserves'
import OrderInformation from '@/components/user/order/View.vue'

export default {
  name: 'TruckManagePage',
  data() {
    return {
      isShowDialog: false,
      isEdit: false,
      selected: null,
      order: {
        bookTruckInformation: {}
      },
      orders: [],
      orderStatusText,
      orderTypes: [
        { label: 'Đơn chưa xác nhận', value: 1 },
        { label: 'Đơn đang giao', value: 2 },
        { label: 'Đơn đã giao', value: 3 },
        { label: 'Đơn đã bị huỷ', value: 4 }
      ],
      orderTypeFilter: 1
    }
  },
  components: {
    OrderInformation
  },
  methods: {
    ...mapActions('driver', {
      getListOrder: 'getListOrder',
      getOrder: 'getOrder',
      acceptOrder: 'acceptOrder',
      getListSuggestTruck: 'getListSuggestTruck',
      acceptSuggestTruck: 'acceptSuggestTruck',
      cancelOrder: 'cancelOrder',
      completeOrder: 'completeOrder'
    }),
    async onView(id) {
      const { data } = await this.getOrder(id)
      const { completed_at } = data
      const format = completed_at && completed_at.substring(0, 16).split(' ').join('T')
      this.order = convertToCamelCase({ ...data, completed_at: format })
      this.isEdit = true
      this.isShowDialog = true
    },
    onAccept() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'success',
        title: 'Xác nhận đồng ý ?',
        text: 'Bạn có chắc chắn đồng ý đơn hàng này ?',
        accept: this.actionAccept,
        acceptText: 'Xác nhận',
        cancelText: 'Thoát'
      })
    },
    onCancel() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Xác nhận huỷ đơn?',
        text: 'Bạn có chắc chắn huỷ đơn hàng này ?',
        accept: this.actionCancel,
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
    clearEvent() {
      this.order = {
        bookTruckInformation: {}
      }
      this.isEdit = false
      this.isShowDialog = false
      this.isDelete = false
    },
    async actionAccept() {
      await this.acceptOrder(this.selected.order_information_id)
      await this.onSearch()
    },
    async actionCancel() {
      await this.cancelOrder(this.selected.order_information_id)
      await this.onSearch()
    },
    async actionComplete() {
      await this.completeOrder(this.selected.order_information_id)
      await this.onSearch()
      this.clearEvent()
    },
    async onSearch() {
      const { data } = await this.getListOrder(this.orderTypeFilter)
      this.orders = [...data].map(order => {
        const { from_city, to_city } = order
        const location = `${from_city} - ${to_city}`
        return { ...order, location }
      })
    }
  },
  async created() {
    await this.onSearch()
  }
}
</script>
