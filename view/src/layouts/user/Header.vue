<template>
  <div class="main-header">
    <div class="relative">
      <div
        class="text-gray-300 flex items-end py-4 px-2 bg-transparent xl:px-28"
        :class="{
          'fixed w-full top-0 left-0 bg-white text-gray-900 pt-0 duration-700 shadow-lg z-10': isFixedHeader,
          'bg-black': $route.fullPath !== '/home'
        }"
      >
        <div class="header-logo w-1/5">
          <img
            @click="$router.push('/')"
            :src="
              isFixedHeader
                ? require('@/assets/img/user/logo-header-dark.svg')
                : require('@/assets/img/user/logo-header.svg')
            "
            alt="logo"
            class="h-16 cursor-pointer hover:opacity-80"
          />
        </div>
        <div class="header-tabs w-3/5 text-center">
          <span
            v-for="(tab, index) in tabs"
            :key="index"
            class="tab cursor-pointer hover:text-red-600 mx-3"
            :class="{ 'text-red-600': $route.fullPath.search(tab.slug) != -1 }"
            @click="changeTab(tab)"
          >
            {{ tab.label }}
          </span>
        </div>
        <div class="header-action w-1/5 flex justify-end">
          <span class="material-icons-outlined cursor-pointer hover:text-red-600" @click="isSearch = !isSearch">
            {{ isSearch ? 'close' : 'search' }}
          </span>
          <Notification :notifications="notifications" />
          <vs-dropdown v-if="isLoggedIn" color="danger" class="hover:text-red-600">
            <span class="material-icons-outlined cursor-pointer mx-2">account_circle</span>
            <vs-dropdown-menu>
              <vs-dropdown-item>
                <div class="flex ml-1 w-max" @click="$router.push('/page/profile')">
                  <span class="material-icons-outlined">account_circle</span>
                  <span class="ml-2">Tài khoản của bạn</span>
                </div>
              </vs-dropdown-item>
              <vs-dropdown-item v-if="isDriver">
                <div class="flex ml-1 w-max">
                  <span class="material-icons-outlined">local_shipping</span>
                  <span class="ml-2" @click="$router.push('/order-management')">Quản lý đơn hàng</span>
                </div>
              </vs-dropdown-item>
              <vs-dropdown-item v-else>
                <div class="flex ml-1 w-max">
                  <span class="material-icons-outlined">local_shipping</span>
                  <span class="ml-2" @click="$router.push('/reservation')">Quản lý đơn đặt</span>
                </div>
              </vs-dropdown-item>
              <vs-dropdown-item v-if="isDriver">
                <div class="flex ml-1 w-max">
                  <span class="material-icons-outlined">description</span>
                  <span class="ml-2" @click="$router.push('/driver-management')">Bài viết của bạn</span>
                </div>
              </vs-dropdown-item>
              <vs-dropdown-item>
                <div class="flex ml-1 w-max" @click="logout">
                  <span class="material-icons-outlined">logout</span>
                  <span class="ml-2">Đăng xuất</span>
                </div>
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
          <div v-else>
            <span @click="$router.push('/login')" class="cursor-pointer ml-6 hover:text-red-600">Đăng nhập</span>
            <span @click="$router.push('/register')" class="cursor-pointer ml-6 hover:text-red-600">Đăng ký</span>
          </div>
        </div>
      </div>
      <div
        class="input-header left-0 w-full animation-height-show z-10"
        v-if="isSearch"
        :class="{ 'fixed w-full top-20 left-0 z-10': isFixedHeader, 'absolute top-24': !isFixedHeader }"
      >
        <label class="flex items-center bg-gray-800 w-full px-4 md:px-32">
          <input class="py-4 bg-gray-800 w-full text-gray-300 leading-7" placeholder="Tìm kiếm..." />
          <span class="material-icons-outlined cursor-pointer mx-4 text-gray-300 hover:text-red-600">search</span>
        </label>
      </div>
    </div>
  </div>
</template>

<script>
import tabs from './header-tabs'
import { mapActions, mapGetters } from 'vuex'
import Notification from '@/components/common/Notification.vue'

export default {
  name: 'header-main',
  data() {
    return {
      tabs: tabs,
      isSearch: false
    }
  },
  components: {
    Notification
  },
  computed: {
    ...mapGetters({
      notifications: 'notification/client'
    }),
    profile() {
      return JSON.parse(localStorage.getItem('profileClient')) || this.$store.state.clientAuth.profile
    },
    isFixedHeader() {
      return this.$store.state.app.scroll.scrollY && this.$store.state.app.scroll.scrollY > 170
    },
    isLoggedIn() {
      return this.$store.state.clientAuth.token || localStorage.getItem('tokenClient')
    },
    isDriver() {
      return this.isLoggedIn && this.profile.customer_type == 0
    }
  },
  methods: {
    ...mapActions({
      logout: 'clientAuth/logout',
      getNotifications: 'notification/getNotificationsForClient'
    }),
    changeTab(tab) {
      this.$router.push(tab.slug)
    }
  },
  async created() {
    if (this.isLoggedIn) await this.getNotifications()
  }
}
</script>
<style lang="scss" scoped>
.main-header {
  span.content {
    line-height: 1;
  }
}
</style>
