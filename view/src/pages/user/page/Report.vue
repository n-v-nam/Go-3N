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
      <vs-select class="w-1/2 mb-6" label="Chủ đề" v-model="option">
        <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item, index) in options" />
      </vs-select>
      <vs-input v-if="option == 1" class="w-1/2 mb-6" v-model="phone" label-placeholder="Số điện thoại bị báo cáo" />
      <vs-input class="mb-6 w-2/3 font-bold" label-placeholder="Tiêu đề" v-model="title" />
      <vs-textarea class="mb-6" label="Nội dung chi tiết" placeholder="Chi tiết nội dung" v-model="content" />
      <p class="italic font-thin mb-2 text-sm">Cung cấp thêm bằng chứng về mặt hình ảnh:</p>
      <input type="file" class="w-1/2 block mb-6" @change="handleImage" />

      <vs-button color="danger" class="w-32" icon="email" @click="onReport">Gửi</vs-button>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { createFormData } from '@/helpers/form-data'

export default {
  name: 'report-message',
  data() {
    return {
      content: '',
      title: '',
      options: [
        { text: 'Báo cáo tài xế, khách hàng', value: 1 },
        { text: 'Báo cáo lỗi, đóng góp tính năng,...', value: 0 }
      ],
      option: 1,
      phone: '',
      image: ''
    }
  },
  created() {
    if (this.$route.query.phone) {
      this.phone = this.$route.query.phone
      this.option = 1
    }
  },
  methods: {
    ...mapActions({
      createReport: 'report/createReport'
    }),
    async onReport() {
      if (!this.option) this.phone = ''

      let number = this.phone.split('')
      if (number[0] == 0) number[0] = '+84'

      const payload = {
        phone: number.join(''),
        title: this.title,
        content: this.content,
        report_type: this.option
      }
      await this.createReport(createFormData(payload))
      this.clearEvent()
    },
    handleImage(e) {
      const image = e.target.files[0]
      this.image = image
    },
    clearEvent() {
      this.title = ''
      this.content = ''
      this.phone = ''
      this.image = null
    }
  }
}
</script>
