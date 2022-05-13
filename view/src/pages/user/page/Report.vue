<template>
  <div class="report-message my-10">
    <div class="subtitle text-center py-2 font-bold px-10 bg-red-100 border-2 border-red-600 rounded">
      <p class="text-xl font-bold underline">Lời cảm ơn:</p>
      <p>Chúng tôi luôn đánh giá cao và lắng nghe những góp ý từ khách hàng !</p>
      <p>Mọi ý kiến của khách hàng đều được giải đáp và trả lời một cách sớm nhất.</p>
      <p>Cảm ơn các bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi !</p>
    </div>
    <div class="content mx-10 bg-gray-50 my-10 p-10 pb-20 rounded">
      <p class="text-center text-2xl font-bold text-red-600 my-4 underline">Gửi báo cáo sự cố, góp ý:</p>
      <vs-input
        class="mb-6 w-2/3 font-bold"
        label="Chủ đề"
        placeholder="VD: Báo cáo tài xế, góp ý tính năng..."
        v-model="title"
      />
      <vs-textarea class="mb-6" label="Nội dung chi tiết" placeholder="VD: Chi tiết nội dung" v-model="content" />
      <vs-button color="danger" class="w-32" icon="email" @click="onReport">Gửi</vs-button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'report-message',
  data() {
    return {
      content: '',
      title: ''
    }
  },
  methods: {
    onReport() {
      const { id } = JSON.parse(localStorage.getItem('profileClient')) || this.$store.state.clientAuth.profile
      const payload = {
        id,
        title: this.title,
        content: this.content
      }
      console.log(payload)
    }
  }
}
</script>
