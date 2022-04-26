<!-- @format -->

<template>
  <div class="customer-manage">
    <TitlePage title="Quản lý khách hàng" icon="person" />
    <div class="customer-content">
      <vs-table noDataText="Chưa có dữ liệu khách hàng" v-model="selected" class="border-2 border-red-200 mt-4" pagination max-items="10" :data="customers">
        <template slot="header">
          <div class="flex justify-between items-center m-2 mb-8 w-full">
            <div @click="onCreate" class="flex items-center justify-center p-2 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 border-blue-400 border-2">
              <span class="material-icons text-green-600 mx-2"> person_add </span>
              <span class="font-bold">Thêm khách hàng</span>
            </div>
            <div>
              <vs-input type="text" icon="search" @keyup.enter="onSearch" v-model="searchFilter" placeholder="Tìm kiếm theo SĐT..." />
            </div>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="id"> STT </vs-th>
          <vs-th sort-key="name"> Tên </vs-th>
          <vs-th sort-key="phone"> Số điện thoại </vs-th>
          <vs-th sort-key="type"> Loại khách hàng </vs-th>
          <vs-th sort-key="is_verified"> Xác thực </vs-th>
          <vs-th>Hành động</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="prop" :key="index" v-for="(prop, index) in data">
            <vs-td :data="data[index].id">
              {{ data[index].id }}
            </vs-td>
            <vs-td :data="data[index].name">
              {{ data[index].name }}
            </vs-td>
            <vs-td :data="data[index].phone">
              {{ data[index].phone }}
            </vs-td>
            <vs-td :data="data[index].customer_type">
              {{ data[index].customer_type | customerType }}
            </vs-td>
            <vs-td :data="data[index].is_verified">
              {{ data[index].is_verified ? 'Đã xác thực' : 'Chưa xác thực' }}
            </vs-td>
            <vs-td>
              <span class="material-icons mr-2 text-blue-600 hover:text-black" @click="onEdit(prop.id)"> edit </span>
              <span class="material-icons text-red-400 hover:text-black" @click="onDelete()"> delete_forever </span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <vs-popup :title="isCreate ? 'Thêm khách hàng' : 'Chỉnh sửa khách hàng'" :active.sync="isShowDialog" button-close-hidden>
      <CustomerDetail :customer="customer" @clearEvent="clearEvent" @actionCreate="actionCreate" @actionEdit="actionEdit" @actionDelete="onDelete" />
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import CustomerDetail from '@/components/admin/customer/CustomerDetail.vue'

export default {
  name: 'CustomerManagePage',
  data() {
    return {
      isShowDialog: false,
      isEdit: false,
      isCreate: false,
      customers: [],
      selected: null,
      customer: {},
      searchFilter: null
    }
  },
  components: {
    CustomerDetail
  },
  methods: {
    ...mapActions('customer', {
      getCustomers: 'getCustomers',
      getCustomer: 'getCustomer',
      createCustomer: 'createCustomer',
      updateCustomer: 'updateCustomer',
      deleteCustomer: 'deleteCustomer',
      searchCustomer: 'searchCustomer'
    }),
    async onEdit(id) {
      const res = await this.getCustomer(id)
      this.customer = res.data
      this.isEdit = true
      this.isCreate = false
      this.isShowDialog = true
    },
    onDelete() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Xác nhận xoá ?',
        text: 'Bạn có chắc chắn muốn xoá người dùng này ?',
        accept: this.actionDelete,
        acceptText: 'Xoá',
        cancelText: 'Thoát'
      })
    },
    onCreate() {
      this.customer = {}
      this.isCreate = true
      this.isEdit = false
      this.isShowDialog = true
    },
    clearEvent() {
      this.customer = {}
      this.isCreate = false
      this.isEdit = false
      this.isShowDialog = false
      this.isDelete = false
    },
    async actionCreate() {
      let formData = new FormData()
      formData.append('name', this.customer.name)
      formData.append('sex', this.customer.sex)
      formData.append('phone', this.customer.phone)
      formData.append('customer_type', this.customer.customer_type)
      formData.append('avatar', this.customer.avatar)
      formData.append('password', this.customer.password)
      await this.createCustomer(formData)
      await this.fetchCustomers()
      this.clearEvent()
    },
    async actionEdit() {
      let formData = new FormData()
      formData.append('_method', 'PUT')
      formData.append('id', this.customer.id)
      formData.append('name', this.customer.name)
      formData.append('sex', this.customer.sex)
      formData.append('phone', this.customer.phone)
      formData.append('customer_type', this.customer.customer_type)
      formData.append('avatar', this.customer.avatar)
      formData.append('password', this.customer.password)
      await this.updateCustomer(formData)
      await this.fetchCustomers()
      this.clearEvent()
    },
    async actionDelete() {
      await this.deleteCustomer(this.selected.id)
      await this.fetchCustomers()
      this.clearEvent()
    },
    async fetchCustomers() {
      const customers = await this.getCustomers()
      this.customers = customers.data
    },
    async onSearch() {
      const res = await this.searchCustomer({ email: this.searchFilter })
      this.customers = res.data
    }
  },
  async created() {
    await this.fetchCustomers()
  }
}
</script>
