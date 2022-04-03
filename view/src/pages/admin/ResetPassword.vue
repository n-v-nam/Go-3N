<!-- @format -->

<template>
  <div id="reset-password" class="mx-2 mt-32">
    <div class="flex items-center border-2 rounded-xl py-20 mx-20 bg-gray-100">
      <vs-col vs-w="6" class="flex justify-center items-center">
        <img src="@/assets/img/reset-password.png" alt="" />
      </vs-col>
      <vs-col vs-w="4" class="border-l-2">
        <div class="flex flex-col mt-20">
          <div class="font-semibold text-center text-4xl">Thay đổi mật khẩu</div>
          <div class="flex flex-col justify-center items-center">
            <vs-input type="password" label="Mật khẩu" class="w-3/4 mt-12" v-model="password" />
            <vs-input type="password" label="Nhắc lại mật khẩu" class="w-3/4 mt-4" v-model="rePassword" />
          </div>
          <div class="flex justify-evenly items-center mt-8">
            <vs-button @click="onChangePassword">Đổi mật khẩu</vs-button>
            <span class="cursor-pointer hover:text-gray-600" @click="onBack">Bạn đã có tài khoản ?</span>
          </div>
        </div>
      </vs-col>
    </div>
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
      setErrorNotification: 'app/setErrorNotification'
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
  },
  created() {
    // if (!this.$route.query.token) {
    //   this.$router.push('/login')
    //   this.setErrorNotification('Bạn không thể truy cập trưc tiếp trạng này !')
    // }
  }
}
</script>
