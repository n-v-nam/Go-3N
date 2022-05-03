<template>
  <div id="payment-confirm" class="loading-money flex my-10 bg-gray-100 p-4 rounded-lg">
    <div class="image w-1/2 p-16">
      <img class="drop-shadow-img" src="@/assets/img/user/payment.png" alt="" />
    </div>
    <div v-if="isSuccess" class="w-1/2 flex justify-center items-center flex-col border-l-2 border-gray-200">
      <p class="font-bold text-2xl mb-20">Giao dịch thực hiện thành công!</p>
      <div class="w-full flex justify-center gap-10">
        <vs-button icon="account_balance_wallet" @click="$router.push('/page/profile')">Kiểm tra số dư</vs-button>
        <vs-button color="danger" icon="home" @click="$router.push('/home')">Trở về trang chủ</vs-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'confirm-payment',
  data() {
    return {
      isSuccess: true
    }
  }
}
</script>
