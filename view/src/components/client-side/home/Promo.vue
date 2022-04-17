<template>
  <div id="promo" class="mx-2 md:mx-10 xl:mx-28 text-white font-oswald mt-32">
    <vs-col vs-w="6">
      <div class="px-4">
        <p class="title uppercase font-bold text-4xl mb-4">Vận chuyển hàng</p>
        <p class="mb-1">Bạn đang cần tìm đơn vị vận chuyển hàng hóa nhanh với giá rẻ?</p>
        <p class="mb-1">Doanh nghiệp của bạn đang muốn tìm một công ty có đa dạng loại xe vận chuyển để đáp ứng nhiều nhu cầu giao hàng khác nhau?</p>
        <p class="mb-1">Bạn chưa biết nên chọn đơn vị giao hàng chuyên nghiệp, uy tín?</p>
        <vs-button color="danger" @click="$router.push('/booking')" class="font-bold text-xl mt-4">
          <p class="flex items-center justify-evenly px-2">
            <span>Tìm ngay tại đây</span>
            <span class="material-icons ml-4 text-2xl"> zoom_in </span>
          </p>
        </vs-button>
      </div>
    </vs-col>
  </div>
</template>

<script>
export default {
  name: 'home-promp',
  data() {
    return {}
  }
}
</script>

<style lang="scss" scoped>
#promo {
  .container-promo {
    background-color: rgba(32, 29, 29, 0.8);
    border-radius: 5px;
    margin-bottom: 20px;
  }
}
</style>
