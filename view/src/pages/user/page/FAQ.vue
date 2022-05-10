<template>
  <div class="faq grid grid-cols-4 my-10 justify-start">
    <div class="sidebar">
      <div class="input flex items-center mb-5 p-5 bg-gray-100">
        <vs-input class="mx-2" placeholder="Bạn cần hỗ trợ gì ?" v-model="searchFilter" />
        <vs-button icon="search" color="danger"></vs-button>
      </div>
      <div class="tabs px-5 py-2 bg-gray-100">
        <p
          v-for="(tab, index) in tabs"
          :key="index"
          @click="$router.push(`#${tab.hash}`)"
          class="tab my-3 p-2 cursor-pointer hover:text-red-600 font-bold"
        >
          {{ tab.name }}
        </p>
      </div>
    </div>
    <div class="content col-start-2 col-end-5 place-self-center">
      <img class="h-72" src="@/assets/img/user/faq.png" alt="faq" />
      <p class="font-bold text-center text-xl text-red-600 my-10">Vui lòng liên hệ đội ngũ phát triển</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'faq',
  data() {
    return {
      searchFilter: '',
      content: '',
      tabs: [
        {
          name: 'Các câu hỏi phổ biến',
          hash: 'populate'
        },
        { name: 'Báo cáo', hash: 'report' },
        { name: 'Liên hệ phát triển', hash: 'contact' }
      ]
    }
  },
  watch: {
    '$route.hash'(hash) {
      const tab = this.tabs.find(tab => `#${tab.hash}` === hash)
      this.content = tab
    }
  }
}
</script>
