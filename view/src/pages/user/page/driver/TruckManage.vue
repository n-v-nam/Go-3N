<!-- @format -->

<template>
  <div id="truck-manage" class="my-20">
    <div class="header flex mt-10">
      <vs-icon class="text-2xl mr-2" icon="arrow_right"></vs-icon>
      <p class="font-bold text-2xl">Quản lý xe của bạn</p>
    </div>
    <div class="truck-content">
      <vs-table noDataText="Chưa có dữ liệu xe" v-model="selected" class="border-2 border-red-200 mt-4" pagination max-items="10" :data="trucks">
        <template slot="header">
          <div class="flex justify-between items-center m-2 mb-8 w-full">
            <div @click="onCreate" class="flex items-center justify-center p-2 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 border-blue-400 border-2">
              <span class="material-icons text-green-600 mx-2"> local_shipping </span>
              <span class="font-bold">Thêm xe</span>
            </div>
            <div>
              <!-- <vs-select placeholder="VD: Xe 10 tấn" label="Loại xe" v-model="statusFilter" class="mb-4 pr-2 w-full">
                <vs-select-item :key="index" :value="item.value" :text="item.name" v-for="(item, index) in statusList" />
              </vs-select> -->
            </div>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="truck_id"> STT </vs-th>
          <vs-th sort-key="license_plates"> Biển số xe </vs-th>
          <vs-th sort-key="name"> Tên </vs-th>
          <vs-th sort-key="customer_id"> Trạng thái</vs-th>
          <vs-th sort-key="category_truck_id">Loại xe</vs-th>
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
            <vs-td :data="data[index].name">
              {{ data[index].name }}
            </vs-td>
            <vs-td :data="data[index].status">
              {{ data[index].status ? 'Đã duyệt' : 'Chưa duyệt' }}
            </vs-td>
            <vs-td :data="data[index].category_truck_id">
              {{ getCategoryName(data[index].category_truck_id) }}
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
      <TruckForm :truck="truck" :owner="owner" @clearEvent="clearEvent" @actionCreate="actionCreate" @actionEdit="actionEdit" @actionDelete="onDelete" />
    </vs-popup>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { convertToCamelCase, convertToSnackCase } from '@/helpers/convert-keys'
import { createFormData } from '@/helpers/form-data'
import TruckForm from '@/components/user/truck/TruckForm.vue'

export default {
  name: 'TruckManagePage',
  data() {
    return {
      isShowDialog: false,
      isEdit: false,
      isCreate: false,
      trucks: [],
      selected: null,
      truck: {
        customerId: null
      },
      owner: {}
    }
  },
  computed: {
    ...mapGetters({
      categoryTrucks: 'categoryTruck/getCategoryTrucks'
    })
  },
  components: {
    TruckForm
  },
  methods: {
    ...mapActions('driver', {
      getTrucks: 'getTrucksOfDriver',
      createTruck: 'createTruckByDriver',
      updateTruck: 'updateTruckByDriver',
      deleteTruck: 'deleteTruckByDriver'
    }),
    async onEdit(id) {
      const res = await this.getTruck(id)
      this.truck = convertToCamelCase(res.data.truck_information)
      this.owner = convertToCamelCase(res.data.customer_information)
      this.isEdit = true
      this.isCreate = false
      this.isShowDialog = true
    },
    onCreate() {
      this.truck = {
        customerId: null
      }
      this.isCreate = true
      this.isEdit = false
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
    clearEvent() {
      this.truck = {
        customerId: null
      }
      this.isCreate = false
      this.isEdit = false
      this.isShowDialog = false
      this.isDelete = false
    },
    async actionCreate() {
      await this.createTruck(createFormData(convertToSnackCase(this.truck)))
      await this.onSearchByStatus()
      this.clearEvent()
    },
    async actionEdit() {
      await this.updatePost(createFormData(convertToSnackCase(this.truck)), true)
      await this.onSearch()
      this.clearEvent()
    },
    async actionDelete() {
      await this.deletePost(this.selected.truck_id)
      await this.onSearch()
      this.clearEvent()
    },
    async onSearch() {
      const res = await this.getTrucks()
      this.trucks = res.data
    },
    getCategoryName(id) {
      const category = this.categoryTrucks.find((categoryTruck) => categoryTruck.category_truck_id == id)
      return category.name
    }
  },
  async created() {
    await this.onSearch()
  }
}
</script>
<style lang="scss">
.vuesax-app-is-ltr .vs-table--search-input {
  border: 2px solid #ccc !important;
}
</style>
