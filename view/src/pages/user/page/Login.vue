<template>
  <div id="login" class="flex py-20 border-b-4 items-center">
    <div class="img-sub w-1/2 mx-10">
      <img src="@/assets/img/user/slide-1.png" alt="" class="w-full p-8" />
    </div>
    <div class="form-container w-1/2 px-10 py-20 bg-gray-100 rounded">
      <p class="titile text-3xl font-bold text-red-600 mb-2">Đăng nhập</p>
      <p class="sub-title mb-8">Tiết kiệm lên đến 15% với các giải pháp thuê xe chở hàng của chúng tôi, phạm vi phủ sóng toàn quốc và đội ngũ hỗ trợ tận tâm</p>
      <label class="w-full block mb-6">
        <input
          type="text"
          class="px-4 py-3 rounded border-gray-400 border-2 w-full"
          placeholder="Số điện thoại"
          v-validate="'required|numeric|min:10|max:11'"
          name="phone"
          data-vv-as="Số điện thoại"
          v-model="phone"
        />
        <span class="text-red-600 text-sm mx-2">{{ errors.first('phone') }}</span>
      </label>
      <label class="w-full block mb-6">
        <input
          type="password"
          class="px-4 py-3 rounded border-gray-400 border-2 w-full"
          placeholder="Mật khẩu"
          name="password"
          v-validate="'required|min:8'"
          data-vv-as="Mật khẩu"
          v-model="password"
        />
        <span class="text-red-600 text-sm mx-2">{{ errors.first('password') }}</span>
      </label>
      <vs-button color="danger" class="w-full mb-4" @click="onLogin"> Đăng nhập </vs-button>
      <p class="text-lg">
        Bạn chưa có tài khoản?
        <span @click="$router.push('/register')" class="text-red-600 font-bold cursor-pointer hover:text-black"> Đăng kí ngay !</span>
      </p>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  data() {
    return {
      phone: '',
      password: ''
    }
  },
  methods: {
    ...mapActions({
      login: 'authClient/login'
    }),
    async onLogin() {
      let phone = this.phone.split('')
      if (phone[0] == 0) phone[0] = '+84'
      const customer = {
        phone: phone.join(''),
        password: this.password
      }
      await this.login(customer)
    }
  },
  created() {
    if (this.$store.state.authClient.token || sessionStorage.getItem('token')) this.$router.push('/home')
  }
}
</script>
