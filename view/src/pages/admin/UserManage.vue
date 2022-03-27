<!-- @format -->

<template>
  <div class="user-manage">
    <TitlePage title="User Management" icon="person" />
    <div class="user-content">
      <vs-table :sst="true" v-model="selected" class="border-2 border-red-200 mt-4" multiple :total="users.length" pagination max-items="3" search :data="users">
        <template slot="header" class="flex">
          <div @click="onCreate" class="flex items-center justify-center p-2 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 m-2 mb-8 border-blue-400 border-2">
            <span class="material-icons text-green-600 mx-2"> person_add </span>
            <span class="font-bold">Thêm người dùng</span>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="id"> Id </vs-th>
          <vs-th sort-key="name"> Name </vs-th>
          <vs-th sort-key="email"> Email </vs-th>
          <vs-th sort-key="type"> Role </vs-th>
          <vs-th>Action</vs-th>
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
              {{ data[index].type }}
            </vs-td>
            <vs-td>
              <span class="material-icons mr-2 text-blue-600 hover:text-black" @click="onEdit(prop.id)"> edit </span>
              <span class="material-icons text-red-400 hover:text-black" @click="onDelete(prop.id)"> delete_forever </span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <vs-popup :title="isCreate ? 'Add User' : 'Edit User'" :active.sync="isShowDialog" button-close-hidden>
      <UserDetail :user="user" :userType="userType" @clearEvent="clearEvent" @actionCreate="actionCreate" @actionEdit="actionEdit" @actionDelete="actionDelete" />
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import USER_TYPE from '@/constants/user'
import UserDetail from '@/components/user/UserDetail.vue'

export default {
  name: 'UserManagePage',
  data() {
    return {
      isShowDialog: false,
      isEdit: false,
      isCreate: false,
      isDelete: false,
      users: [],
      selected: [],
      userType: USER_TYPE,
      user: {}
    }
  },
  components: {
    UserDetail
  },
  methods: {
    ...mapActions({
      getUsers: 'user/getUsers',
      getUser: 'user/getUser',
      createUser: 'user/createUser'
    }),
    async onEdit(id) {
      const res = await this.getUser(id)
      this.user = res.data
      this.isEdit = true
      this.isCreate = false
      this.isShowDialog = true
    },
    onDelete() {
      this.isDelete = true
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
      await this.createUser({ user: this.user })
      await this.fetchUsers()
      this.clearEvent()
    },
    async actionEdit() {},
    async actionDelete() {},
    async fetchUsers() {
      const users = await this.getUsers()
      this.users = users.data.map((user) => {
        user.type = USER_TYPE[user.type]
        return user
      })
    }
  },
  async created() {
    await this.fetchUsers()
  }
}
</script>
<style lang="scss">
.vuesax-app-is-ltr .vs-table--search-input {
  border: 2px solid #ccc !important;
}
</style>
