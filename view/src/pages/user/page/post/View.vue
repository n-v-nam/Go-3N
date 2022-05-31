<template>
  <div id="post-view" class="mt-10">
    <p v-if="isSearch" :key="isSearch" class="flex items-center gap-5 mb-6 mt-10 bg-red-50 cursor-pointer hover:text-red-600 w-max px-2 py-1 rounded font-bold" @click="$router.push('/booking')">
      <span class="material-icons text-red-600">keyboard_backspace</span>
      Trở về trang tìm kiếm
    </p>
    <vs-col vs-w="5">
      <Slide :images="post.postImage" />
      <p v-if="post.postImage && post.postImage.length" class="font-light italic text-center mb-5 mt-2">
        Một số hình ảnh do tài xế cung cấp
      </p>
      <p v-else class="font-light italic text-center mb-5 mt-2">Bài viết này không có hình ảnh nào</p>
    </vs-col>
    <vs-col vs-w="7">
      <div class="px-10 ml-5 mb-10 rounded">
        <p class="title font-bold py-2 text-3xl border-b-2 bg-gray-200 rounder px-2">{{ post.title }}</p>
        <div class="ml-3 mt-2">
          <p class="inline-block">
            <span class="font-bold">Tên tài xế:</span>
            {{ owner.name }}
          </p>
          <p class="inline-block mx-2">
            <span class="font-bold">SĐT:</span>
            {{ owner.phone }}
          </p>
          <div class="location flex items-center">
            <p class="mr-2 font-bold">Xe chạy theo tuyến:</p>
            <p>{{ fromLocation }}</p>
            <span class="material-icons mx-2">arrow_right_alt</span>
            <p>{{ toLocation }}</p>
          </div>
          <p class="">
            <span class="font-bold">Loại chuyến:</span>
            {{ post.postType ? 'Chấp nhận ghép hàng' : 'Không nhận ghép hàng' }}
          </p>
          <p class="content">Nội dung thêm: {{ post.content }}</p>
          <p class="orther flex flex-col ml-4 mb-4">
            <span class="item-type">- Loại hàng nhận: {{ itemType }}</span>
            <span class="item-type">- Tổng trọng tải: {{ post.weightProduct }} tấn</span>
            <span class="item-type">- Loại xe: {{ truck.categoryTruck }}</span>
            <span class="item-type">- Biển số xe: {{ truck.licensePlates }}</span>
          </p>
          <p class="italic font-light">*Bài viết hết {{ isExpried ? 'đã' : 'sẽ' }} hạn vào ngày {{ post.endDate.substring(0, 10) }}</p>
          <div class="price flex items-center mt-10 drop-shadow-container">
            <p class="text-2xl font-bold mr-4">Giá:</p>
            <div v-if="post.lowestPrice && post.highestPrice" class="flex items-center">
              <p class="py-1 px-2 rounded font-bold bg-red-600 text-white">{{ post.lowestPrice }} VNĐ</p>
              <span class="material-icons font-bold mx-2">arrow_right_alt</span>
              <p class="py-1 px-2 rounded font-bold bg-red-600 text-white">{{ post.highestPrice }} VNĐ</p>
            </div>
            <p v-else class="py-1 px-2 rounded font-bold bg-red-600 text-white">Giá thoả thuận</p>
          </div>
          <div class="action mt-10 pb-20">
            <vs-button icon="arrow_circle_right" :disabled="isExpried" color="success" @click="onBooking">
              Đặt chuyến
            </vs-button>
            <vs-button icon="question_answer" class="mx-4">Liên hệ tài xế</vs-button>
          </div>
        </div>
      </div>
    </vs-col>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { convertToCamelCase } from '@/helpers/convert-keys'
import Slide from '@/components/common/Slide'

export default {
  name: 'post-view',
  data() {
    return {
      post: {},
      owner: {},
      truck: {},
      isSearch: true
    }
  },
  components: {
    Slide
  },
  computed: {
    itemType() {
      return this.post.postItemType ? Object.values(this.post.postItemType).join(', ') : null
    },
    fromLocation() {
      return this.post.fromDistrict ? `${this.post.fromDistrict}, ${this.post.fromCity}` : this.post.fromCity
    },
    toLocation() {
      return this.post.toDistrict ? `${this.post.toDistrict}, ${this.post.toCity}` : this.post.toCity
    },
    isExpried() {
      return new Date() > new Date(this.post.endDate)
    }
  },
  methods: {
    ...mapActions({
      getPost: 'post/viewPost',
      createReserse: 'reservation/createReserse'
    }),
    async onBooking() {
      const { postId } = this.$route.params
      await this.createReserse(postId)
    }
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      if(from.path.search('booking') == -1) {
        vm.isSearch = false
      }
    }) 
  },
  async created() {
    const { postId } = this.$route.params
    const { data } = await this.getPost(postId)
    this.post = convertToCamelCase({ ...data.post_information })
    this.truck = convertToCamelCase({ ...data.truck_information })
    this.owner = convertToCamelCase({ ...data.customer_information })
  }
}
</script>
