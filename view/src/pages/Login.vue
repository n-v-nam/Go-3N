<!-- @format -->

<template>
  <div id="login" class="mx-2">
    <vs-row class="h-screen">
      <vs-col vs-w="8" class="flex flex-col justify-center items-center h-full">
        <img src="@/assets/img/logo.svg" alt="" />
      </vs-col>
      <vs-col vs-w="4" class="bg-green-500 h-full rounded-xl">
        <div class="flex flex-col mt-56">
          <div class="font-semibold text-center text-white text-4xl">ADMIN ĐĂNG NHẬP</div>
          <div class="flex flex-col justify-center items-center">
            <vs-input label="Email" class="w-3/4 mt-16" v-model="email" />
            <vs-input type="password" label="Mật khẩu" class="w-3/4 mt-4" v-model="password" />
          </div>
          <div class="flex justify-evenly items-center mt-8">
            <vs-button @click="handleLogin">Đăng nhập</vs-button>
            <span class="text-white cursor-pointer hover:text-gray-300" @click="onResetPassword">Quên mật khẩu ?</span>
          </div>
        </div>
      </vs-col>
    </vs-row>
    <div class="dialog-reset">
      <vs-popup title="Nhận email đă đăng kí trong hệ thống" :active.sync="isResetPassword">
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
  }
}
</script>
