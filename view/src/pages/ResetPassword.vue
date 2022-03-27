<!-- @format -->

<template>
  <div class="reset-password">
    <vs-row class="h-screen">
      <vs-col vs-w="8" class="flex flex-col justify-center items-center h-full">
        <img src="@/assets/img/reset-password.png" alt="" />
      </vs-col>
      <vs-col vs-w="4" class="bg-green-500 h-full rounded-xl">
        <div class="flex flex-col mt-56">
          <div class="font-semibold text-center text-white text-4xl">Reset Password</div>
          <div class="flex flex-col justify-center items-center">
            <vs-input type="password" label="Mật khẩu" class="w-3/4 mt-16" v-model="password" />
            <vs-input type="password" label="Nhắc lại mật khẩu" class="w-3/4 mt-4" v-model="rePassword" />
          </div>
          <div class="flex justify-evenly items-center mt-8">
            <vs-button @click="onChangePassword">Đổi mật khẩu</vs-button>
            <span class="text-white cursor-pointer hover:text-gray-300" @click="onBack">Bạn đã có tài khoản ?</span>
          </div>
        </div>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'reset-password',
  data() {
    return {
      password: null,
      rePassword: null
    }
  },
  methods: {
    ...mapActions({
      changePassword: 'auth/changePassword',
      setErrorNotification: 'auth/setErrorNotification'
    }),
    onBack() {
      this.$router.push('/login')
    },
    async onChangePassword() {
      if (this.password !== this.rePassword) {
        this.setErrorNotification('Mật khẩu 2 lần nhập chưa trùng khớp !')
      } else {
        await this.changePassword({ token: this.$route.query.token, password: this.password })
      }
    }
  }
}
</script>
