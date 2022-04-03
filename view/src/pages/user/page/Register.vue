<template>
  <div id="login" class="py-10 border-b-4">
    <div class="form-container flex flex-col justify-center items-center w-full py-10 bg-gray-100 rounded">
      <div class="title">
        <span class="text-red-600 font-bold leading-loose text-2xl md:text-4xl">--</span>
        <span class="text-xl md:text-3xl font-bold text-black mb-2 mx-6">Nhập thông tin đăng ký</span>
        <span class="text-red-600 font-bold leading-loose text-4xl">--</span>
      </div>
      <p class="sub-title mb-8 text-xs md:text-sm mx-2">
        Tiết kiệm lên đến 15% với các giải pháp thuê xe chở hàng của chúng tôi, phạm vi phủ sóng toàn quốc và đội ngũ hỗ
        trợ tận tâm
      </p>
      <div class="flex w-full">
        <div class="col w-1/2 mx-4 md:mx-10 2xl:mx-20">
          <label class="w-full block mb-6">
            <input
              type="text"
              class="px-4 py-3 rounded border-gray-400 border-2 w-full"
              placeholder="Họ và tên"
              v-validate="'required'"
              name="name"
              data-vv-as="Họ và tên"
              v-model="customer.name"
            />
            <span class="text-red-600 text-sm mx-2">{{ errors.first('name') }}</span>
          </label>
          <label class="w-full block mb-6">
            <input
              type="text"
              class="px-4 py-3 rounded border-gray-400 border-2 w-full"
              placeholder="Số điện thoại"
              name="phone"
              v-validate="'required'"
              data-vv-as="Số điện thoại"
              v-model="customer.phone"
            />
            <span class="text-red-600 text-sm mx-2">{{ errors.first('phone') }}</span>
          </label>
          <div class="sex-select flex justify-start items-center mb-6">
            <label class="mr-4 font-bold">Giới tính:</label>
            <vs-radio
              v-for="(sexType, index) of sexTypes"
              :key="index"
              class="mx-2"
              color="danger"
              vs-name="sexType"
              :vs-value="index"
              v-model="customer.sex"
              >{{ sexType }}</vs-radio
            >
          </div>
          <div class="sex-select flex justify-start mb-6">
            <label class="mr-4 font-bold">Loại khách hàng:</label>
            <div class="radio-group flex flex-col 2xl:flex-row">
              <vs-radio
                v-for="(customerType, index) of customerTypes"
                :key="index"
                class="mx-2 justify-start mb-2"
                color="danger"
                vs-name="customerType"
                :vs-value="index"
                v-model="customer.customer_type"
                >{{ customerType }}</vs-radio
              >
            </div>
          </div>
        </div>
        <div class="col w-1/2 mx-4 md:mx-10 2xl:mx-20">
          <label class="w-full block mb-6">
            <input
              type="password"
              class="px-4 py-3 rounded border-gray-400 border-2 w-full"
              placeholder="Mật khẩu"
              name="password"
              v-validate="'required|min:8'"
              data-vv-as="Mật khẩu"
              v-model="customer.password"
            />
            <span class="text-red-600 text-sm mx-2">{{ errors.first('password') }}</span>
          </label>
          <label class="w-full block mb-6">
            <input
              type="password"
              class="px-4 py-3 rounded border-gray-400 border-2 w-full"
              placeholder="Nhắc lại mật khẩu"
              name="reRassword"
              v-model="rePassword"
            />
            <span v-show="!isCorrectRePassword" class="text-red-600 text-sm mx-2"
              >Nhắc lại mật khẩu chưa trùng khớp</span
            >
          </label>
          <div class="flex">
            <vs-checkbox color="danger inline-block" class="mb-6 justify-start" v-model="isAgreeTerm"> </vs-checkbox>
            <span>
              Tôi đồng ý với các
              <span @click.passive="$router.push('/term-policie')" class="text-red-600 cursor-pointer"
                >điều khoản và chính sách</span
              >
              của dịch vụ.
            </span>
          </div>
          <vs-button @click="onRegister" :disabled="!validateForm" color="danger" class="w-full mb-4">
            Đăng ký
          </vs-button>
          <p class="text-lg">
            Bạn đã có tài khoản?
            <span @click="$router.push('/login')" class="text-red-600 font-bold cursor-pointer hover:text-black">
              Đăng nhập ngay!</span
            >
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { CUSTOMER_TYPE, SEX_TYPE } from '@/constants/customer'
export default {
  data() {
    return {
      customer: { name: '', email: '', password: '', sex: 1, customer_type: 1 },
      rePassword: '',
      isAgreeTerm: false,
      customerTypes: CUSTOMER_TYPE,
      sexTypes: SEX_TYPE
    }
  },
  computed: {
    isCorrectRePassword() {
      return (this.customer.password == this.rePassword && this.customer.password) || !this.rePassword
    },
    validateForm() {
      return !this.errors.any() && this.isAgreeTerm
    }
  },
  methods: {
    onRegister() {}
  }
}
</script>
