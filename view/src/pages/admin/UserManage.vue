<!-- @format -->

<template>
  <div class="user-manage">
    <TitlePage title="Quản lý nhân sự" icon="manage_accounts" />
    <div class="user-content">
      <vs-table
        noDataText="Chưa có dữ liệu nhân sự"
        v-model="selected"
        class="border-2 border-red-200 mt-4"
        pagination
        max-items="10"
        :data="users"
      >
        <template slot="header">
          <div class="flex justify-between items-center m-2 mb-8 w-full">
            <div
              @click="onCreate"
              class="flex items-center justify-center p-2 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 border-blue-400 border-2"
            >
              <span class="material-icons text-green-600 mx-2">person_add</span>
              <span class="font-bold">Thêm người dùng</span>
            </div>
            <div>
              <vs-input
                type="text"
                icon="search"
                @keyup.enter="onSearch"
                v-model="searchFilter"
                placeholder="Tìm kiếm theo email..."
              />
            </div>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="id">STT</vs-th>
          <vs-th sort-key="name">Tên</vs-th>
          <vs-th sort-key="email">Email</vs-th>
          <vs-th sort-key="type">Chức danh</vs-th>
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
            <vs-td :data="data[index].email">
              {{ data[index].email }}
            </vs-td>
            <vs-td :data="data[index].type">
              {{ data[index].type | userRole }}
            </vs-td>
            <vs-td>
              <span class="material-icons mr-2 text-blue-600 hover:text-black" @click="onEdit(prop.id)">edit</span>
              <span class="material-icons text-red-400 hover:text-black" @click="onDelete()">delete_forever</span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <vs-popup
      :title="isCreate ? 'Thêm người dùng' : 'Chỉnh sửa người dùng'"
      :active.sync="isShowDialog"
      button-close-hidden
    >
      <UserDetail
        :user="user"
        @clearEvent="clearEvent"
        @actionCreate="actionCreate"
        @actionEdit="actionEdit"
        @actionDelete="onDelete"
      />
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import UserDetail from '@/components/admin/user/View.vue'

export default {
  name: 'UserManagePage',
  data() {
    return {
      isShowDialog: false,
      isEdit: false,
      isCreate: false,
      users: [],
      selected: null,
      user: {},
      searchFilter: null
    }
  },
  components: {
    UserDetail
  },
  methods: {
    ...mapActions('user', {
      getUsers: 'getUsers',
      getUser: 'getUser',
      createUser: 'createUser',
      updateUser: 'updateUser',
      deleteUser: 'deleteUser',
      searchUser: 'searchUser'
    }),
    async onEdit(id) {
      const res = await this.getUser(id)
      this.user = res.data
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
      this.user = {}
      this.isCreate = true
      this.isEdit = false
      this.isShowDialog = true
    },
    clearEvent() {
      this.user = {}
      this.isCreate = false
      this.isEdit = false
      this.isShowDialog = false
      this.isDelete = false
    },
    async actionCreate() {
      await this.createUser(this.user)
      await this.fetchUsers()
      this.clearEvent()
    },
    async actionEdit() {
      await this.updateUser(this.user)
      await this.fetchUsers()
      this.clearEvent()
    },
    async actionDelete() {
      await this.deleteUser(this.selected.id)
      await this.fetchUsers()
      this.clearEvent()
    },
    async fetchUsers() {
      const users = await this.getUsers()
      this.users = users.data
    },
    async onSearch() {
      await this.searchUser({ email: this.searchFilter })
    }
  },
  async created() {
    await this.fetchUsers()
  }
}
</script>
