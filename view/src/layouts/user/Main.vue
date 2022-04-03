<template>
  <div id="user-main" class="scroll-smooth" v-scroll="onScroll">
    <div class="relative" :class="{ 'header-book': $route.fullPath == '/home' }">
      <About />
      <Header />
      <div class="overlay"></div>
    </div>
    <div class="content">
      <router-view></router-view>
    </div>
    <Footer />
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import About from './About.vue'
import Header from './Header.vue'
import Footer from './Footer.vue'
export default {
  data() {
    return {}
  },
  components: {
    Header,
    About,
    Footer
  },
  methods: {
    ...mapActions({
      setScroll: 'app/setScroll'
    }),
    onScroll() {
      this.setScroll({ scrollX: window.scrollX, scrollY: window.scrollY })
    }
  }
}
</script>
<style lang="scss">
#user-main {
  .header-book {
    background-image: url('../../assets/img/user/bg-image-main.jpeg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center 0px;
    background-size: cover;
    z-index: 1;
    min-height: 100vh;
  }
}
</style>
