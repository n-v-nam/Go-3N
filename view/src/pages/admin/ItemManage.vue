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
        :data="itemTypes"
      >
        <template slot="header">
          <div class="flex justify-between items-center m-2 mb-8 w-full">
            <div
              @click="onCreate"
              class="flex items-center justify-center p-2 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 border-blue-400 border-2"
            >
              <span class="material-icons text-green-600 mx-2">category</span>
              <span class="font-bold">Thêm loại hàng</span>
            </div>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="item_type_id">STT</vs-th>
          <vs-th sort-key="name">Tên loại xe</vs-th>
          <vs-th sort-key="slug">Slug</vs-th>
          <vs-th>Hành động</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="prop" :key="index" v-for="(prop, index) in data">
            <vs-td :data="data[index].item_type_id">
              {{ data[index].item_type_id }}
            </vs-td>
            <vs-td :data="data[index].name">
              {{ data[index].name }}
            </vs-td>
            <vs-td :data="data[index].slug">
              {{ data[index].slug }}
            </vs-td>
            <vs-td>
              <span class="material-icons mr-2 text-blue-600 hover:text-black" @click="onEdit(prop.item_type_id)">
                edit
              </span>
              <span class="material-icons text-red-400 hover:text-black" @click="onDelete()">delete_forever</span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <vs-popup :title="isCreate ? 'Thêm xe' : 'Chỉnh sửa xe'" :active.sync="isShowDialog" button-close-hidden>
      <ItemTypeDetail
        :itemType="itemType"
        @clearEvent="clearEvent"
        @actionCreate="actionCreate"
        @actionEdit="actionEdit"
        @actionDelete="onDelete"
      />
    </vs-popup>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { convertToCamelCase, convertToSnackCase } from '@/helpers/convert-keys'
import ItemTypeDetail from '@/components/admin/item/View.vue'

export default {
  name: 'TruckManagePage',
  data() {
    return {
      isShowDialog: false,
      isEdit: false,
      isCreate: false,
      selected: null,
      itemType: {}
    }
  },
  computed: {
    ...mapGetters({
      itemTypes: 'item/itemTypes'
    })
  },
  components: {
    ItemTypeDetail
  },
  methods: {
    ...mapActions('item', {
      fetchItemTypes: 'fetchItemTypes',
      createItemType: 'createItemType',
      updateItemType: 'updateItemType',
      deleteItemType: 'deleteItemType'
    }),
    async onEdit(id) {
      const data = this.itemTypes.find(itemType => itemType.item_type_id == id)
      this.itemType = convertToCamelCase(data)
      this.isEdit = true
      this.isCreate = false
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
    onCreate() {
      this.itemType = {}
      this.isCreate = true
      this.isEdit = false
      this.isShowDialog = true
    },
    clearEvent() {
      this.itemType = {}
      this.isCreate = false
      this.isEdit = false
      this.isShowDialog = false
      this.isDelete = false
    },
    async actionCreate() {
      await this.createItemType(convertToSnackCase(this.itemType))
      await this.fetchItemTypes()
      this.clearEvent()
    },
    async actionEdit() {
      await this.updateItemType(convertToSnackCase(this.itemType))
      await this.fetchItemTypes()
      this.clearEvent()
    },
    async actionDelete() {
      await this.deleteItemType(this.selected.item_type_id)
      await this.fetchItemTypes()
      this.clearEvent()
    }
  },
  async created() {
    await this.fetchItemTypes()
  }
}
</script>
