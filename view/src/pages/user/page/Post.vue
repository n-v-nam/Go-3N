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
          <PostForm />
        </vs-col>
      </div>
    </div>
    <vs-popup class="holamundo" title="Chi tiết bài đăng" :active.sync="isShowDetailPost">
      <PostForm />
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
      postSelected: null,
      truckId: null
    }
  },
  methods: {
    ...mapActions({}),
    onShow(id) {
      this.postSelected = this.posts.find((post) => post.id == id)
      this.isShowDetailPost = true
    },
    onScrollToPostAdd() {
      const offset = this.$refs.postAdd.offsetTop - 350
      window.scrollTo({ top: offset, behavior: 'smooth' })
    }
  },
  created() {}
}
</script>

<style></style>
