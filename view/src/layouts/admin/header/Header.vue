<!-- @format -->

<template>
  <div class="header-container left-60 top-4 ml-4 rounded border-yellow-300 border-2 fixed right-4 bg-yellow-100 flex items-center justify-between h-14 z-10">
    <div class="search">
      <span class="material-icons text-yellow-500 mx-2 text-3xl"> grade </span>
    </div>
    <div class="user-info flex justify-center items-center mr-2">
      <span class="font-semibold mx-2">{{ userInfo.name }}</span>
      <vs-dropdown>
        <vs-avatar class="mt-3" />
        <vs-dropdown-menu class="w-max">
          <vs-dropdown-item>
            <div class="flex justify-start items-center" @click="$router.push('/admin-profile')">
              <span class="material-icons mx-2 text-xl"> person </span>
              Thông tin người dùng
            </div>
          </vs-dropdown-item>
          <vs-dropdown-item>
            <div class="flex justify-start items-center" @click="handleLogout">
              <span class="material-icons mx-2 text-xl"> logout </span>
              Đăng xuất
            </div>
          </vs-dropdown-item>
        </vs-dropdown-menu>
      </vs-dropdown>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'Header',
  computed: {
    userInfo() {
      return this.profile() || JSON.parse(localStorage.getItem('profileAdmin'))
    }
  },
  methods: {
    ...mapActions({
      logout: 'auth/logout',
      getProfile: 'auth/getProfile'
    }),
    ...mapGetters({
      profile: 'auth/profile'
    }),
    async handleLogout() {
      await this.logout()
    }
  },
  async created() {
    await this.getProfile()
  }
}
</script>
