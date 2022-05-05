<!-- @format -->

<template>
  <div class="truck-manage">
    <TitlePage title="Quản lý loại hàng" icon="category" />
    <div class="truck-content">
      <vs-table
        noDataText="Chưa có dữ liệu loại xe"
        v-model="selected"
        class="border-2 border-red-200 mt-4"
        pagination
        max-items="10"
        :data="orders"
      >
        <template slot="thead">
          <vs-th sort-key="order_information_id">STT</vs-th>
          <vs-th sort-key="driver_phone" class="th-phone">
            <p>SĐT người đặt</p>
            <p>SĐT tài xế</p>
          </vs-th>
          <vs-th sort-key="item_type">Loại hàng</vs-th>
          <vs-th sort-key="location">Loại hàng</vs-th>
          <vs-th sort-key="post_id">Mã bài</vs-th>
          <vs-th sort-key="weight_product">Trọng lượng (Kg)</vs-th>
          <vs-th sort-key="status">Trạng thái</vs-th>
          <vs-th>Hành động</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="prop" :key="index" v-for="(prop, index) in data">
            <vs-td :data="data[index].order_information_id">
              {{ data[index].order_information_id }}
            </vs-td>
            <vs-td :data="data[index].customer_phone">
              <p class="">{{ data[index].customer_phone }}</p>
              {{ data[index].driver_phone }}
            </vs-td>
            <vs-td :data="data[index].item_type">
              {{ data[index].item_type }}
            </vs-td>
            <vs-td :data="data[index].location">
              {{ data[index].location }}
            </vs-td>
            <vs-td :data="data[index].post_id">
              {{ data[index].post_id }}
            </vs-td>
            <vs-td :data="data[index].weight_product">
              {{ data[index].weight_product }}
            </vs-td>
            <vs-td :data="data[index].status">
              {{ orderStatusText[data[index].status] }}
            </vs-td>
            <vs-td>
              <span
                class="material-icons mr-2 text-blue-600 hover:text-black"
                @click="onEdit(prop.order_information_id)"
              >
                edit
              </span>
              <span class="material-icons text-red-400 hover:text-black" @click="onDelete()">delete_forever</span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <vs-popup title="Chỉnh sửa xe" :active.sync="isShowDialog" button-close-hidden>
      <OrderDetail :order="order" @clearEvent="clearEvent" @actionEdit="actionEdit" @actionDelete="onDelete" />
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { convertToCamelCase, convertToSnackCase } from '@/helpers/convert-keys'
import { orderStatusText } from '@/constants/reserves'
import OrderDetail from '@/components/admin/order/View.vue'

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
      orderStatusText
    }
  },
  components: {
    OrderDetail
  },
  methods: {
    ...mapActions('order', {
      getOrders: 'getOrders',
      getOrder: 'getOrder',
      updateOrder: 'updateOrder',
      deleteOrder: 'deleteOrder'
    }),
    async onEdit(id) {
      const { data } = await this.getOrder(id)
      const { completed_at } = data
      const format = completed_at && completed_at.substring(0, 16).split(' ').join('T')
      this.order = convertToCamelCase({ ...data, completed_at: format })
      this.isEdit = true
      this.isShowDialog = true
    },
    onDelete() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Xác nhận xoá ?',
        text: 'Bạn có chắc chắn muốn xoá loại hàng này ?',
        accept: this.actionDelete,
        acceptText: 'Xoá',
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
    async actionEdit() {
      const { orderInformationId, completedAt, status } = this.order
      await this.updateOrder(
        convertToSnackCase({
          orderId: orderInformationId,
          completedAt,
          status
        })
      )
      await this.fetchOrders()
      this.clearEvent()
    },
    async actionDelete() {
      await this.deleteOrder(this.selected.order_information_id)
      await this.fetchOrders()
      this.clearEvent()
    },
    async fetchOrders() {
      const { data } = await this.getOrders(1)
      this.orders = [...data].map(order => {
        const { from_city, to_city } = order
        const location = `${from_city} - ${to_city}`
        return { ...order, location }
      })
    }
  },
  async created() {
    await this.fetchOrders()
  }
}
</script>
<style lang="scss" scoped>
.th-phone ::v-deep > .vs-table-text {
  display: flex;
  flex-direction: column !important;
  align-items: flex-start;
  gap: 0;
}
</style>
