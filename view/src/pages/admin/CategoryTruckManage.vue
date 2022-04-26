<!-- @format -->

<template>
  <div class="truck-manage">
    <TitlePage title="Quản lý xe" icon="local_shipping" />
    <div class="truck-content">
      <vs-table noDataText="Chưa có dữ liệu loại xe" v-model="selected" class="border-2 border-red-200 mt-4" pagination max-items="10" :data="categoryTrucks">
        <template slot="header">
          <div class="flex justify-between items-center m-2 mb-8 w-full">
            <div @click="onCreate" class="flex items-center justify-center p-2 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 border-blue-400 border-2">
              <span class="material-icons text-green-600 mx-2"> local_shipping </span>
              <span class="font-bold">Thêm loại xe</span>
            </div>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="category_truck_id"> STT </vs-th>
          <vs-th sort-key="name">Tên loại xe</vs-th>
          <vs-th sort-key="slug"> Slug </vs-th>
          <vs-th>Hành động</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="prop" :key="index" v-for="(prop, index) in data">
            <vs-td :data="data[index].category_truck_id">
              {{ data[index].category_truck_id }}
            </vs-td>
            <vs-td :data="data[index].name">
              {{ data[index].name }}
            </vs-td>
            <vs-td :data="data[index].slug">
              {{ data[index].slug }}
            </vs-td>
            <vs-td>
              <span class="material-icons mr-2 text-blue-600 hover:text-black" @click="onEdit(prop.category_truck_id)"> edit </span>
              <span class="material-icons text-red-400 hover:text-black" @click="onDelete()"> delete_forever </span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <vs-popup :title="isCreate ? 'Thêm xe' : 'Chỉnh sửa xe'" :active.sync="isShowDialog" button-close-hidden>
      <CategoryTruckDetail :categoryTruck="categoryTruck" @clearEvent="clearEvent" @actionCreate="actionCreate" @actionEdit="actionEdit" @actionDelete="onDelete" />
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { convertToCamelCase, convertToSnackCase } from '@/helpers/convert-keys'
import CategoryTruckDetail from '@/components/admin/category-truck/CategoryTruckDetail.vue'

export default {
  name: 'TruckManagePage',
  data() {
    return {
      isShowDialog: false,
      isEdit: false,
      isCreate: false,
      categoryTrucks: [],
      selected: null,
      categoryTruck: {}
    }
  },
  components: {
    CategoryTruckDetail
  },
  methods: {
    ...mapActions('categoryTruck', {
      fetchCategoryTrucks: 'fetchCategoryTrucks',
      createCategoryTruck: 'createCategoryTruck',
      updateCategoryTruck: 'updateCategoryTruck',
      deleteCategoryTruck: 'deleteCategoryTruck'
    }),
    async onEdit(id) {
      const data = this.categoryTrucks.find((categoryTruck) => categoryTruck.category_truck_id == id)
      this.categoryTruck = convertToCamelCase(data)
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
      this.categoryTruck = {}
      this.isCreate = true
      this.isEdit = false
      this.isShowDialog = true
    },
    clearEvent() {
      this.categoryTruck = {}
      this.isCreate = false
      this.isEdit = false
      this.isShowDialog = false
      this.isDelete = false
    },
    async actionCreate() {
      await this.createCategoryTruck(convertToSnackCase(this.categoryTruck))
      const res = await this.fetchCategoryTrucks()
      this.categoryTrucks = res.data
      this.clearEvent()
    },
    async actionEdit() {
      await this.updateCategoryTruck(convertToSnackCase(this.categoryTruck))
      const res = await this.fetchCategoryTrucks()
      this.categoryTrucks = res.data
      this.clearEvent()
    },
    async actionDelete() {
      await this.deleteCategoryTruck(this.selected.category_truck_id)
      const res = await this.fetchCategoryTrucks()
      this.categoryTrucks = res.data
      this.clearEvent()
    }
  },
  async created() {
    const res = await this.fetchCategoryTrucks()
    this.categoryTrucks = res.data
  }
}
</script>
