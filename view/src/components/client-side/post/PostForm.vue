<template>
  <div class="post-form mb-10">
    <div class="px-10 py-4 bg-gray-100 rounded-lg">
      <vs-input class="w-full" label="Tiêu đề" color="danger" placeholder="Tiêu đề của bài viết"></vs-input>
      <vs-select placeholder="VD: Xe 10 tấn" class="" label="Xe sử dụng" v-model="truckId">
        <vs-select-item v-model="truckId" :key="index" :value="item.id" :text="item.name" v-for="(item, index) in trusksOfDiriver" />
      </vs-select>
      <vs-row>
        <vs-col vs-w="6">
          <vs-select placeholder="Vị trí cuối cùng trả hàng" class="selectExample" label="Nơi trả hàng" v-model="fromCityId">
            <vs-select-item :key="index" :value="item.city_id" :text="item.name" v-for="(item, index) in cities" />
          </vs-select>
        </vs-col>
        <vs-col vs-w="6">
          <vs-select placeholder="Vị trí cuối cùng trả hàng" class="selectExample" label="Nơi trả hàng" v-model="toCityId">
            <vs-select-item :key="index" :value="item.city_id" :text="item.name" v-for="(item, index) in cities" />
          </vs-select>
        </vs-col>
      </vs-row>
      <vs-row>
        <vs-col vs-w="6">
          <vs-input class="mb-2" label="Giá từ (VND)" color="danger" placeholder="VD: 100000"></vs-input>
        </vs-col>
        <vs-col vs-w="6">
          <vs-input class="mb-2" label="Giá tối đa (VND)" color="danger" placeholder="VD: 500000"></vs-input>
        </vs-col>
      </vs-row>
      <vs-textarea class="w-full" label="Chi tiết bài đăng" color="danger" placeholder="Nói thêm về cách thức giao hàng, nhận hàng,..."></vs-textarea>
      <vs-button color="danger">Tạo bài viết</vs-button>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  props: {
    post: Object
  },
  data() {
    return {
      trusksOfDiriver: [],
      truckId: null,
      fromCityId: null,
      toCityId: null
    }
  },
  computed: {
    ...mapGetters({
      cities: 'truck/getCity'
    })
  },
  methods: {
    ...mapActions({
      getTrucksOfDriver: 'driver/getTrucksOfDriver',
      getCityName: 'truck/getCityName'
    })
  },
  async created() {
    await this.getCityName()
    const res = await this.getTrucksOfDriver()
    this.trusksOfDiriver = res.data
  }
}
</script>
