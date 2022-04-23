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
        <span v-if="inProgressForgetPassword" @click="isForgetPassword = true" class="text-red-600 font-bold cursor-pointer hover:text-black"> Quên mật khẩu ?</span>
        <span v-else @click="onResume" class="text-red-600 font-bold cursor-pointer hover:text-black"> Tiếp tục quá lấy lại mật khẩu({{ onStep }}/3)</span>
      </p>
      <p class="text-lg">
        Bạn chưa có tài khoản?
        <span @click="$router.push('/register')" class="text-red-600 font-bold cursor-pointer hover:text-black"> Đăng kí ngay !</span>
      </p>
    </div>
    <vs-popup :active.sync="isForgetPassword" title="Quên mật khẩu" class="text-center">
      <p class="">Nhập vào số điện thoại đã đăng kí tài khoản của bạn:</p>
      <input class="w-1/2 text-center border-2 rounded p-2 text-lg mt-10" placeholder="Số điện thoại" v-model="phoneForget" />
      <div class="mt-6">
        <vs-button color="danger" class="px-8" @click="onForgetPassword">Gửi</vs-button>
      </div>
    </vs-popup>
    <vs-popup title="Nhập mã code" class="text-center" button-close-hidden :active.sync="isEnterCode">
      <p class="title">Chúng tôi đã gửi đến số điện thoại của bạn một mã code 6 chữ số</p>
      <p class="title">Vui lòng kiểm tra và nhập vào dưới để lấy lại mật khẩu</p>
      <input :maxlength="6" type="text" class="w-1/2 h-16 tracking-widest text-2xl bg-gray-200 text-center" v-model="code" />
      <div class="mb-2 mt-10">
        <vs-button :disabled="!code" color="danger" class="px-10" @click="onConfirmForgetPassword">Xác nhận</vs-button>
      </div>
    </vs-popup>
    <vs-popup title="Mật khẩu mới" button-close-hidden :active.sync="isConfirmNewPassword">
      <p class="title mb-8">Nhập mật khẩu mới mà bạn muốn thay đổi vào đây:</p>
      <vs-input label-placeholder="Mật khẩu" type="password" class="w-full tracking-widest bg-gray-200 mb-8" v-model="newPassword" />
      <vs-input label-placeholder="Nhập lại mật khẩu" type="password" class="w-full tracking-widest bg-gray-200" v-model="rePassword" />
      <div class="mb-2 mt-10">
        <vs-button :disabled="!isValidNewPassword" color="danger" class="px-10" @click="onConfirmNewPassword">Xác nhận mật khẩu</vs-button>
      </div>
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { convertPhone } from '@/helpers/convert-phone'
export default {
  data() {
    return {
      isForgetPassword: false,
      isEnterCode: false,
      isConfirmNewPassword: false,
      phone: '',
      phoneForget: '',
      password: '',
      code: '',
      newPassword: '',
      rePassword: '',
      onStep: 0
    }
  },
  computed: {
    isValidNewPassword() {
      return this.newPassword && this.newPassword.length > 7 && this.newPassword == this.rePassword
    },
    inProgressForgetPassword() {
      return !this.onStep || this.isEnterCode || this.isForgetPassword || this.isConfirmNewPassword
    }
  },
  methods: {
    ...mapActions({
      login: 'clientAuth/login',
      forgetPassword: 'clientAuth/forgetPassword',
      confirmForgetPassword: 'clientAuth/confirmForgetPassword',
      confirmNewPassword: 'clientAuth/confirmNewPassword',
      setSuccessNotification: 'app/setSuccessNotification'
    }),
    async onLogin() {
      let phone = this.phone.split('')
      if (phone[0] == 0) phone[0] = '+84'
      const customer = {
        phone: phone.join(''),
        password: this.password
      }
      await this.login(customer)
    },
    async onForgetPassword() {
      const phone = convertPhone(this.phoneForget)

      const res = await this.forgetPassword({ phone })
      if (res) {
        this.setSuccessNotification(res.message)
        this.onStep = 1
        this.isForgetPassword = false
        this.isEnterCode = true
      }
    },
    async onConfirmForgetPassword() {
      const res = await this.confirmForgetPassword({ phone: convertPhone(this.phoneForget), verification_code: this.code })
      if (res) {
        this.setSuccessNotification(res.message)
        this.onStep = 2
        this.isEnterCode = false
        this.isConfirmNewPassword = true
      }
    },
    async onConfirmNewPassword() {
      const res = await this.confirmNewPassword({ phone: convertPhone(this.phoneForget), newPassword: this.newPassword })
      if (res) {
        this.setSuccessNotification(res.message)
        this.onStep = 0
        this.isConfirmNewPassword = false
        this.phone = this.phoneForget
      }
    },
    onResume() {
      if (this.onStep == 1) this.isEnterCode = true
      else this.isConfirmNewPassword = true
    }
  },
  created() {
    if (this.$store.state.clientAuth.token || localStorage.getItem('tokenClient')) this.$router.push('/home')
  }
}
</script>
