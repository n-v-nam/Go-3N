<template>
  <div class="loading-money flex my-10 bg-gray-100 p-4 rounded-lg">
    <div class="image w-1/2 p-16">
      <img class="drop-shadow-img" src="@/assets/img/user/payment.png" alt="" />
    </div>
    <div class="content w-1/2">
      <p class="headding font-bold text-2xl text-center my-8">Tạo giao dịch nạp tiền mới</p>
      <vs-select class="w-full mb-6" v-model="options.bankCode" label="Chọn ngân hàng">
        <vs-select-item :text="bank.name" :value="bank.code" v-for="(bank, index) in banks" :key="index">
          {{ bank.name }}
        </vs-select-item>
      </vs-select>
      <vs-input class="w-full mb-6" v-model="options.amount" label="Số tiền" placeholder="Đơn vị: VNĐ" />
      <vs-textarea v-model="options.content" label="Nội dung" placeholder="VD: Chuyển khoản thanh toán XYZ" />
      <vs-button color="danger" :disabled="!resumable" @click="onLoadingMoney">Tiếp tục</vs-button>
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
      loadingMoney: 'payment/loadingMoney',
      saveBill: 'payment/saveBill'
    }),
    async onLoadingMoney() {
      const { amount, bankCode, content } = this.options
      const { data } = await this.loadingMoney(convertToSnackCase({ amount, bankCode, content }))
      window.location.href = data
    }
  },
  async created() {
    const { vnp_BankCode, vnp_Amount, vnp_TxnRef } = this.$route.query
    if (vnp_BankCode && vnp_Amount && vnp_TxnRef) {
      await this.saveBill({ vnp_BankCode, vnp_Amount, vnp_TxnRef })
      this.$router.push('/page/payment/confirm')
    }
  }
}
</script>

<style></style>
