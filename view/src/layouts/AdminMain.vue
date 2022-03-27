<!-- @format -->

<template>
  <div class="admin-main flex">
    <Sidebar />
    <div class="content w-full">
      <Header />
      <div class="main background h-screen ml-64 mr-4 rounded mt-20 border-2">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
import Sidebar from './sidebar/Sidebar.vue'
import Header from './header/Header.vue'

export default {
  name: 'admin-main',
  components: {
    Sidebar,
    Header
  }
}
</script>
