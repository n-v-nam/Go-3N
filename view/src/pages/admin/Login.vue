<!-- @format -->

<template>
  <div id="login" class="mx-2 mt-36">
    <div class="flex items-center border-2 rounded-xl py-20 mx-20 bg-gray-100">
      <vs-col vs-w="6" class="flex justify-center items-center">
        <img src="@/assets/img/logo.svg" alt="" class="w-80" />
      </vs-col>
      <vs-col vs-w="4" class="border-l-2 border-gray-200">
        <div class="flex flex-col">
          <div class="title text-center font-bold text-2xl">Đăng nhập quản trị viên</div>
          <div class="flex flex-col justify-center items-center mt-4">
            <vs-input label="Email" class="w-3/4 mt-6" v-model="email" />
            <vs-input type="password" label="Mật khẩu" class="w-3/4 mt-4" v-model="password" />
          </div>
          <div class="flex justify-evenly items-center mt-8">
            <vs-button @click="handleLogin">Đăng nhập</vs-button>
            <span class="cursor-pointer hover:text-gray-600" @click="onResetPassword">Quên mật khẩu ?</span>
          </div>
        </div>
      </vs-col>
    </div>
    <div class="dialog-reset">
      <vs-popup title="Nhận email đă đăng kí trong hệ thống" :active.sync="isResetPassword" button-close-hidden>
        <vs-input placeholder="Nhập địa chỉ email" v-model="email" />
        <p class="text-red-400">Chúng tôi sẽ gửi thư để lấy lại mật khẩu cho tài khoản của bạn vào email được nhập, nếu như email đã được đăng kí. Hãy vui lòng nhập chính xác !</p>
        <div class="flex justify-end items-center mt-4">
          <vs-button color="primary" icon="email" class="mr-6" @click="resetPassword({ email })">Gửi</vs-button>
          <vs-button color="lightgray" @click="isResetPassword = false">Thoát</vs-button>
        </div>
      </vs-popup>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'Login',
  data() {
    return {
      email: null,
      password: null,
      isResetPassword: false
    }
  },
  methods: {
    ...mapActions({
      login: 'auth/login',
      resetPassword: 'auth/resetPassword'
    }),
    async handleLogin() {
      await this.login({
        email: this.email,
        password: this.password
      })
    },
    onResetPassword() {
      this.isResetPassword = true
    }
  },
  created() {
    if (this.$store.state.authClient.token || sessionStorage.getItem('token')) this.$router.push('/home')
  }
}
</script>
