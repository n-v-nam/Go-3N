<template>
  <div class="post-item-box">
    <vs-card actionable class="m-4" fixedHeight>
      <div slot="header">
        <h3>{{ post.tittle }}</h3>
      </div>
      <div slot="media">
        <img class="h-52 bg-cover bg-fixed" src="@/assets/img/user/slide-1.png" />
      </div>
      <div class="h-20 overflow-hidden">
        <span>{{ post.content }} </span>
      </div>
      <div slot="footer">
        <vs-row vs-justify="flex-end">
          <vs-button size="small" type="gradient" color="danger" @click="$emit('onShow', post.id)" icon="search">Xem</vs-button>
          <vs-button v-if="!isSave" size="small" color="primary" icon="turned_in_not" class="ml-2">Lưu</vs-button>
          <vs-button v-else size="small" color="primary" icon="turned_in" class="ml-2">Lưu</vs-button>
        </vs-row>
      </div>
    </vs-card>
  </div>
</template>

<script>
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
  }
}
</script>
