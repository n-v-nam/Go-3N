<!-- @format -->

<template>
  <div class="header-container left-60 top-4 ml-4 rounded border-yellow-300 border-2 fixed right-4 bg-yellow-100 flex items-center justify-between h-14">
    <div class="search">
      <span class="material-icons text-yellow-500 mx-2 text-3xl"> grade </span>
    </div>
    <div class="user-info flex justify-center items-center mr-2">
      <span class="font-semibold mx-2">Admin</span>
      <vs-dropdown>
        <vs-avatar class="mt-3" />
        <vs-dropdown-menu>
          <vs-dropdown-item>
            <div class="flex justify-center items-center" @click="handleLogout">
              <span class="material-icons mx-2 text-xl"> logout </span>
              Logout
            </div>
          </vs-dropdown-item>
        </vs-dropdown-menu>
      </vs-dropdown>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'Header',
  methods: {
    ...mapActions({
      logout: 'auth/logout'
    }),
    async handleLogout() {
      await this.logout()
    }
  }
}
</script>
