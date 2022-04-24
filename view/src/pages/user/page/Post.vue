<template>
  <div id="post-page">
    <div class="post-outstanding">
      <div class="header text-center">
        <vs-button color="danger" icon="add" class="absolute font-bold" @click="onScrollToPostAdd">Tạo bài đăng của bạn</vs-button>
        <p class="titile font-oswald font-bold text-4xl mt-20 mb-4">
          <span class="text-red-600">-</span>
          Bài đăng nổi bật
          <span class="text-red-600">-</span>
        </p>
        <p class="text-sm text-gray-600">Đây là những bài đăng nổi bật, được tương tác nhiều</p>
        <p class="text-sm text-gray-600 mb-10">Dựa trên tính toán của chúng tôi, nó có thể phù hợp với bạn</p>
      </div>
      <div class="content bg-gray-50">
        <div class="post-containe flex flex-wrap gap-8 justify-center">
          <vs-col v-for="(post, index) in posts" :key="index" vs-w="3">
            <PostItem :post="post" @onShow="onShow" />
          </vs-col>
        </div>
      </div>
    </div>
    <div class="post-add">
      <p class="titile font-oswald font-bold text-center text-4xl mt-20 mb-4">
        <span class="text-red-600">-</span>
        Tạo bài đăng của bạn
        <span class="text-red-600">-</span>
      </p>
      <p class="text-sm text-gray-600 text-center mb-10">Tạo bài viết để chúng tôi đưa bạn gần hơn với khách hàng</p>
      <div class="form-post" ref="postAdd">
        <vs-col vs-w="5" class="text-center">
          <img class="bg-cover bg-fixed" src="@/assets/img/user/slide-1.png" />
        </vs-col>
        <vs-col vs-w="7">
          <PostForm :post="postInitial" />
          <vs-button class="mb-10 w-full" @click="onCreatePost" color="danger">Tạo bài viết</vs-button>
        </vs-col>
      </div>
    </div>
    <vs-popup class="" title="Chi tiết bài đăng" :active.sync="isShowDetailPost">
      <!-- <PostForm :post="postSelected" /> -->
    </vs-popup>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import PostItem from '@/components/client-side/post/PostItem.vue'
import PostForm from '@/components/client-side/post/PostForm.vue'
export default {
  components: {
    PostItem,
    PostForm
  },
  computed: {
    ...mapGetters({})
  },
  data() {
    return {
      isShowDetailPost: false,
      posts: [
        {
          id: '1',
          tittle: 'Post Item',
          content: 'Post Item content'
        },
        {
          id: '2',
          tittle: 'Post Item',
          content: 'Post Item content'
        },
        {
          id: '3',
          tittle: 'Post Item',
          content: 'Post Item content'
        }
      ],
      postSelected: {
        truckId: null,
        titile: '',
        content: '',
        fromCityId: '',
        toCityId: '',
        fromDistrictId: '',
        toDistrictId: '',
        postType: null,
        weightProduct: null,
        lowestPrice: null,
        hightestPrice: null,
        timeDisplay: null
      },
      postInitial: {
        truckId: null,
        titile: '',
        content: '',
        fromCityId: '',
        toCityId: '',
        fromDistrictId: '',
        toDistrictId: '',
        weightProduct: null,
        lowestPrice: null,
        highestPrice: null,
        timeDisplay: null,
        image: [],
        itemTypeId: [],
        postType: 0
      }
    }
  },
  methods: {
    ...mapActions({
      createPost: 'driver/createPostByDriver'
    }),
    onShow(id) {
      this.postSelected = this.posts.find((post) => post.id == id)
      this.isShowDetailPost = true
    },
    onScrollToPostAdd() {
      const offset = this.$refs.postAdd.offsetTop - 350
      window.scrollTo({ top: offset, behavior: 'smooth' })
    },
    async onCreatePost() {
      let formData = new FormData()
      formData.append('truck_id', this.postInitial.truckId)
      formData.append('title', this.postInitial.title)
      formData.append('content', this.postInitial.content)
      formData.append('from_city_id', this.postInitial.fromCityId)
      formData.append('to_city_id', this.postInitial.toCityId)
      formData.append('from_district_id', this.postInitial.fromDistrictId)
      formData.append('to_district_id', this.postInitial.toDistrictId)
      formData.append('weight_product', this.postInitial.weightProduct)
      formData.append('lowest_price', this.postInitial.lowestPrice)
      formData.append('highest_price', this.postInitial.highestPrice)
      formData.append('time_display', this.postInitial.timeDisplay)
      formData.append('item_type_id', this.postInitial.itemTypeId)
      formData.append('image', this.postInitial.image)
      formData.append('post_type', this.postInitial.postType)
      await this.createPost(formData)
      this.clearEvent()
    }
  },
  created() {}
}
</script>

<style></style>
