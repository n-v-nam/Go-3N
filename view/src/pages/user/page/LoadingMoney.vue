<template>
  <div class="loading-money flex my-10 bg-gray-100 p-4 rounded-lg">
    <div class="image w-1/2 p-16">
      <img src="@/assets/img/user/payment.png" alt="" />
    </div>
    <div class="content w-1/2">
      <div class="step-1" v-if="!isPayment">
        <p class="headding font-bold text-2xl text-center my-8">Tạo giao dịch nạp tiền mới</p>
        <vs-select class="w-full mb-6" v-model="options.bankCode" label="Chọn ngân hàng">
          <vs-select-item :text="bank.name" :value="bank.code" v-for="(bank, index) in banks" :key="index">
            {{ bank.name }}
          </vs-select-item>
        </vs-select>
        <vs-input class="w-full mb-6" v-model="options.amount" label="Số tiền" placeholder="Đơn vị: VNĐ" />
        <vs-textarea v-model="options.content" label="Nội dung" placeholder="VD: Chuyển khoản thanh toán XYZ" />
        <vs-button color="danger" :disabled="!resumable" @click="onResume">Tiếp tục</vs-button>
      </div>
      <div class="step-2" v-else>
        <p class="heading font-bold text-2xl text-center my-8">Thanh toán quan ngân hàng {{ bankName }}</p>
        <vs-input
          class="mb-6 w-full"
          label="Số thẻ"
          v-model="options.cardNumber"
          placeholder="VD: 9704198526191432198"
        />
        <vs-input class="mb-6 w-full" label="Ngày phát hành" v-model="options.releaseDate" placeholder="VD: 07/15" />
        <vs-input class="mb-6 w-full" label="Chủ tài khoản" v-model="options.owner" placeholder="VD: NGUYEN VAN A" />
        <vs-button class="w-full" @click="onLoadingMoney">Xác thực</vs-button>
        <vs-divider>Hoặc</vs-divider>
        <vs-button class="mb-6 w-full" color="gray">Huỷ</vs-button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { convertToSnackCase } from '@/helpers/convert-keys'

export default {
  name: 'loading-money',
  data() {
    return {
      isPayment: false,
      options: {
        bankCode: 'ncb'
      },
      banks: [
        {
          name: 'NCB',
          code: 'ncb'
        }
      ]
    }
  },
  computed: {
    bankName() {
      const bank = this.banks.find(bank => bank.code === this.options.bankCode)
      return bank.name
    },
    resumable() {
      const { amount, content } = this.options
      return amount && content
    }
  },
  methods: {
    ...mapActions({
      loadingMoney: 'payment/loadingMoney'
    }),
    onResume() {
      this.isPayment = true
    },
    async onLoadingMoney() {
      const { amount, bankCode, content } = this.options
      await this.loadingMoney(convertToSnackCase({ amount, bankCode, content }))
    }
  }
}
</script>

<style></style>
