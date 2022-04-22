<template>
  <div id="error-page" class="h-screen">
    <div class="relative">
      <About />
      <Header />
      <div class="overlay"></div>
    </div>
    <p class="content text-center text-2xl font-bold mt-28">Trang không được tìm thấy</p>
    <p class="content text-center text-2xl font-bold">Vui lòng kiểm tra lại hoặc liên hệ với quản trị viên</p>
    <Footer class="absolute bottom-0" />
  </div>
</template>
<script>
import About from '@/layouts/user/About.vue'
import Header from '@/layouts/user/Header.vue'
import Footer from '@/layouts/user/Footer.vue'
export default {
  data() {
    return {}
  },
  components: {
    Header,
    About,
    Footer
  }
}
</script>
