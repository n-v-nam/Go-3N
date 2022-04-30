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
            <div>
              <!-- <vs-select placeholder="VD: Xe 10 tấn" label="Loại xe" v-model="statusFilter" class="mb-4 pr-2 w-full">
                <vs-select-item :key="index" :value="item.value" :text="item.name" v-for="(item, index) in statusList" />
              </vs-select> -->
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
              <span v-if="data[index].status" class="material-icons text-red-400 hover:text-black" @click="onDelete()">
                delete_forever
              </span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
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
      orderStatus
    }
  },
  methods: {
    ...mapActions({
      getReserves: 'reservation/getReserves',
      deleteReserve: 'reservation/deleteReserve',
      getReserve: 'reservation/getReserve'
    }),
    onDelete() {
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
    clearEvent() {
      this.isDelete = false
    },
    async actionDelete() {
      await this.deleteReserve(this.selected.order_information_id)
      await this.onSearch()
      this.clearEvent()
    },
    async onSearch() {
      const { data } = await this.getReserves()
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
