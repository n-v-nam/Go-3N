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
          <PostForm :post="post" />
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
import { convertToSnackCase } from '@/helpers/convert-keys'
import { createFormData } from '@/helpers/form-data'
import PostItem from '@/components/user/post/PostItem.vue'
import PostForm from '@/components/user/post/PostForm.vue'
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
      post: {
        image: [],
        itemTypeId: []
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
      const formData = createFormData(convertToSnackCase(this.post), true)
      formData.delete('image')
      for (let i = 0; i < this.post.image.length; i++) {
        formData.append('image[]', this.post.image[i])
      }
      await this.createPost(formData)
      this.clearEvent()
    },
    clearEvent() {
      this.post = { image: [], itemTypeId: [] }
      this.isShowDetailPost = false
    }
  },
  created() {}
}
</script>
