<!-- @format -->

<template>
  <div class="truck-manage">
    <TitlePage title="Quản lý xe" icon="local_shipping" />
    <div class="truck-content">
      <vs-table noDataText="Chưa có dữ liệu xe" v-model="selected" class="border-2 border-red-200 mt-4" pagination max-items="10" :data="trucks">
        <template slot="header">
          <div class="flex justify-between items-center m-2 mb-8 w-full">
            <div @click="onCreate" class="flex items-center justify-center p-2 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 border-blue-400 border-2">
              <span class="material-icons text-green-600 mx-2"> local_shipping </span>
              <span class="font-bold">Thêm xe</span>
            </div>
            <div>
              <vs-input type="text" icon="search" @keyup.enter="onSearch" v-model="searchFilter" placeholder="Tìm kiếm theo biển số..." />
              <vs-select placeholder="VD: Xe 10 tấn" label="Loại xe" v-model="statusFilter" class="mb-4 pr-2 w-full">
                <vs-select-item :key="index" :value="item.value" :text="item.name" v-for="(item, index) in statusList" />
              </vs-select>
            </div>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="truck_id"> STT </vs-th>
          <vs-th sort-key="license_plates"> Biển số xe </vs-th>
          <vs-th sort-key="name"> Tên </vs-th>
          <vs-th sort-key="customer_id"> Trạng thái</vs-th>
          <vs-th sort-key="size">Loại xe</vs-th>
          <vs-th sort-key="weight_items"> Tải trọng</vs-th>
          <vs-th>Hành động</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="prop" :key="index" v-for="(prop, index) in data">
            <vs-td :data="data[index].truck_id">
              {{ data[index].truck_id }}
            </vs-td>
            <vs-td :data="data[index].license_plates">
              {{ data[index].license_plates }}
            </vs-td>
            <vs-td :data="data[index].truck_name">
              {{ data[index].truck_name }}
            </vs-td>
            <vs-td :data="data[index].status">
              {{ data[index].status ? 'Đã duyệt' : 'Chưa duyệt' }}
            </vs-td>
            <vs-td :data="data[index].category_truck">
              {{ data[index].category_truck }}
            </vs-td>
            <vs-td :data="data[index].weight_items">
              {{ data[index].weight_items }}
            </vs-td>
            <vs-td>
              <span class="material-icons mr-2 text-blue-600 hover:text-black" @click="onEdit(prop.truck_id)"> edit </span>
              <span class="material-icons text-red-400 hover:text-black" @click="onDelete()"> delete_forever </span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <vs-popup :title="isCreate ? 'Thêm xe' : 'Chỉnh sửa xe'" :active.sync="isShowDialog" button-close-hidden>
      <TruckDetail :truck="truck" :owner="owner" @clearEvent="clearEvent" @actionCreate="actionCreate" @actionEdit="actionEdit" @actionDelete="onDelete" />
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { convertToCamelCase } from '@/helpers/convert-keys'
import TruckDetail from '@/components/truck-management/TruckDetail.vue'

export default {
  name: 'TruckManagePage',
  data() {
    return {
      isShowDialog: false,
      isEdit: false,
      isCreate: false,
      statusFilter: 0,
      trucks: [],
      statusList: [
        {
          name: 'Chưa duyệt',
          value: 0
        },
        {
          name: 'Đã duyệt',
          value: 1
        }
      ],
      selected: null,
      truck: {},
      searchFilter: null,
      owner: {}
    }
  },
  watch: {
    async statusFilter(val) {
      const res = await this.getTrucks({ status: val })
      this.trucks = res.data
    }
  },
  components: {
    TruckDetail
  },
  methods: {
    ...mapActions('truck', {
      getTrucks: 'getTrucks',
      getTruck: 'getTruck',
      createTruck: 'createTruck',
      updateTruck: 'updateTruck',
      deleteTruck: 'deleteTruck',
      searchTruck: 'searchTruck'
    }),
    async onEdit(id) {
      const res = await this.getTruck(id)
      this.truck = convertToCamelCase(res.data.truck_information)
      this.owner = convertToCamelCase(res.data.customer_information)
      this.isEdit = true
      this.isCreate = false
      this.isShowDialog = true
    },
    onDelete() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Xác nhận xoá ?',
        text: 'Bạn có chắc chắn muốn xoá xe này ?',
        accept: this.actionDelete,
        acceptText: 'Xoá',
        cancelText: 'Thoát'
      })
    },
    onCreate() {
      this.truck = {}
      this.isCreate = true
      this.isEdit = false
      this.isShowDialog = true
    },
    clearEvent() {
      this.truck = {}
      this.isCreate = false
      this.isEdit = false
      this.isShowDialog = false
      this.isDelete = false
    },
    async actionCreate() {
      await this.createTruck(this.truck)
      await this.getTrucks({ status: this.statusFilter })
      this.clearEvent()
    },
    async actionEdit() {
      await this.updateTruck(this.truck)
      await this.getTrucks({ status: this.statusFilter })
      this.clearEvent()
    },
    async actionDelete() {
      await this.deleteTruck(this.selected.truck_id)
      await this.getTrucks({ status: this.statusFilter })
      this.clearEvent()
    },
    async onSearch() {
      await this.searchTruck({ license_plates: this.searchFilter })
    }
  },
  async created() {
    const res = await this.getTrucks({ status: this.statusFilter })
    this.trucks = res.data
  }
}
</script>
<style lang="scss">
.vuesax-app-is-ltr .vs-table--search-input {
  border: 2px solid #ccc !important;
}
</style>
