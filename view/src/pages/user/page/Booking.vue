<template>
  <div id="booking">
    <Map @resultSearch="resultSearch" />
    <div class="posts" v-if="isShowResult">
      <p v-if="!posts.length" class="title font-bold text-2xl font-oswald">
        Rất tiếc, không có bài đăng phù hợp cho bạn.
        <br />
        Hãy thử thay đổi nội dung tìm kiếm hoặc quay lại vào thời gian khác.
      </p>
      <div v-else>
        <p class="title font-bold text-2xl font-oswald">Dưới đây là kết quả tìm kiếm:</p>
        <vs-dropdown class="float-right" vs-custom-content vs-trigger-click>
          <span class="shadow-lg rounded p-2">
            Sắp xếp
            <vs-icon class="" icon="expand_more"></vs-icon>
          </span>
          <vs-dropdown-menu class="w-max">
            <vs-dropdown-item> Gần nhất </vs-dropdown-item>
            <vs-dropdown-item> Giá từ thấp đến cao </vs-dropdown-item>
            <vs-dropdown-item> Giá từ cao đến thấp </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>
        <div class="content mt-10 bg-gray-100 rounded">
          <div class="post-containe flex flex-wrap gap-8 justify-center">
            <vs-col v-for="(post, index) in posts" :key="index" vs-w="3">
              <PostItem :post="post" @onShow="onShow" />
            </vs-col>
          </div>
        </div>
        <div class="pagination mt-6 mb-10">
          <vs-pagination color="danger" :total="total" :max="7" v-model="currentPage"></vs-pagination>
        </div>
        <vs-popup class="holamundo" title="Chi tiết bài đăng" :active.sync="isShowDetailPost">
          <PostForm />
        </vs-popup>
      </div>
    </div>
  </div>
</template>

<script>
import Map from '@/components/user/booking/Map.vue'
import PostItem from '@/components/user/post/PostItem.vue'
import PostForm from '@/components/user/post/PostForm.vue'

export default {
  components: {
    Map,
    PostItem,
    PostForm
  },
  data() {
    return {
      isShowResult: false,
      isShowDetailPost: false,
      postSelected: null,
      posts: [],
      currentPage: 1,
      total: 10
    }
  },
  computed: {},
  methods: {
    onShow(id) {
      this.postSelected = this.posts.find((post) => post.id == id)
      this.isShowDetailPost = true
    },
    resultSearch(posts) {
      this.isShowResult = true
      this.posts = posts ? posts.list_post : []
    }
  }
}
</script>
