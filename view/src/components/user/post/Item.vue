<template>
  <div class="post-item-box">
    <vs-card actionable class="m-4" fixedHeight>
      <div slot="header">
        <h3>{{ post.tittle }}</h3>
      </div>
      <div slot="media">
        <img class="h-52 bg-cover bg-fixed" :src="post.avatar || require(`@/assets/img/user/post/${post.image}`)" />
      </div>
      <div class="h-20 overflow-hidden">
        <span>{{ post.content }}</span>
      </div>
      <div slot="footer">
        <vs-row vs-justify="flex-end">
          <vs-button
            size="small"
            type="gradient"
            color="danger"
            @click="$router.push(`post/view/${post.post_id}`)"
            icon="search"
          >
            Xem
          </vs-button>
          <vs-button
            v-if="!post.isFavorite"
            size="small"
            color="primary"
            icon="turned_in_not"
            @click="onSave"
            class="ml-2"
          >
            Lưu
          </vs-button>
        </vs-row>
      </div>
    </vs-card>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'post-item-box',
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      isSave: false
    }
  },
  methods: {
    ...mapActions({
      addToFavoritePost: 'driver/createFavoritePost'
    }),
    async onSave() {
      await this.addToFavoritePost({ post_id: this.post.post_id })
    }
  }
}
</script>
