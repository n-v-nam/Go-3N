<template>
  <div class="post-form mb-2">
    <div class="px-10 pt-4 pb-16 bg-gray-100 rounded-lg">
      <vs-input
        class="w-full"
        label="Tiêu đề"
        color="danger"
        placeholder="Tiêu đề của bài viết"
        v-model="post.title"
      ></vs-input>
      <div class="post-type flex items-center my-2">
        <span class="mr-4 ml-1 text-gray-600">Loại dịch vụ</span>
        <vs-radio class="mx-4" v-model="post.postType" :vs-value="1" vs-name="post-type">Chấp nhận ghép hàng</vs-radio>
        <vs-radio v-model="post.postType" :vs-value="0" vs-name="post-type">Không ghép hàng</vs-radio>
      </div>
      <vs-row>
        <vs-col vs-w="6">
          <vs-select placeholder="VD: Xe 10 tấn" class="" label="Xe sử dụng" v-model="post.truckId">
            <vs-select-item :key="index" :value="item.truck_id" :text="item.name" v-for="(item, index) in trucks" />
            <p v-if="!this.trucks.length">Bạn chưa có xe nào, vui lòng đăng kí xe trước khi tạo bài viết</p>
          </vs-select>
        </vs-col>
        <vs-col vs-w="6">
          <vs-input placeholder="VD: 5" class="" label="Thời gian hiện thị (ngày)" v-model="post.timeDisplay" />
        </vs-col>
      </vs-row>
      <vs-row>
        <vs-col vs-w="6">
          <vs-select placeholder="VD: Hà Nội" class="" label="Tỉnh nhận hàng" v-model="post.fromCityId">
            <vs-select-item :key="index" :value="index + 1" :text="item" v-for="(item, index) in cities" />
          </vs-select>
        </vs-col>
        <vs-col vs-w="6">
          <vs-select placeholder="VD: Nghệ An" class="" label="Tỉnh trả hàng" v-model="post.toCityId">
            <vs-select-item :key="index" :value="index + 1" :text="item" v-for="(item, index) in cities" />
          </vs-select>
        </vs-col>
      </vs-row>
      <vs-row>
        <vs-col vs-w="6">
          <vs-select placeholder="Huyện nhận hàng" class="" label="Huyện nhận hàng" v-model="post.fromDistrictId">
            <vs-select-item :key="index" :value="index + 1" :text="item" v-for="(item, index) in fromDistricts" />
          </vs-select>
        </vs-col>
        <vs-col vs-w="6">
          <vs-select placeholder="Huyện trả hàng" class="" label="Huyện trả hàng" v-model="post.toDistrictId">
            <vs-select-item :key="index" :value="index + 1" :text="item" v-for="(item, index) in toDistricts" />
          </vs-select>
        </vs-col>
      </vs-row>
      <vs-row>
        <vs-col vs-w="6">
          <vs-input placeholder="VD: 12 tấn" class="" label="Trọng tải hàng" v-model="post.weightProduct" />
        </vs-col>
        <vs-col vs-w="6">
          <vs-select multiple placeholder="Loại hàng nhận" class="" label="Loại hàng yêu cầu" v-model="post.itemTypeId">
            <vs-select-item
              :key="index"
              :value="item.item_type_id"
              :text="item.name"
              v-for="(item, index) in itemTypes"
            />
          </vs-select>
        </vs-col>
      </vs-row>
      <vs-row>
        <vs-col vs-w="6">
          <vs-input
            class="mb-2"
            label="Giá từ (VND)"
            color="danger"
            v-model="post.lowestPrice"
            placeholder="VD: 100000"
          ></vs-input>
        </vs-col>
        <vs-col vs-w="6">
          <vs-input
            class="mb-2"
            label="Giá tối đa (VND)"
            v-model="post.highestPrice"
            color="danger"
            placeholder="VD: 500000"
          ></vs-input>
        </vs-col>
      </vs-row>
      <vs-textarea
        class="w-full"
        label="Chi tiết bài đăng"
        color="danger"
        v-model="post.content"
        placeholder="Nói thêm về cách thức giao hàng, nhận hàng,..."
      ></vs-textarea>
      <div class="">
        <div class="img flex items-center">
          <img :src="img" v-for="(img, index) in srcPreviews" :key="index" class="rounded-full mb-4 w-20 h-20" />
        </div>
        <input multiple type="file" class="hidden" ref="uploadImage" @change="handleUploadImage" />
        <vs-button size="small" icon="add_a_photo" color="gray" @click="$refs.uploadImage.click()">
          Thêm ảnh đính kèm
        </vs-button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  props: {
    post: {
      type: Object
    },
    truck: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      fromDistricts: [],
      toDistricts: [],
      srcPreviews: []
    }
  },
  watch: {
    async 'post.fromCityId'(val) {
      if (val) {
        const res = await this.getDistrict(val)
        this.fromDistricts = Object.values(res.data)
      }
    },
    async 'post.toCityId'(val) {
      if (val) {
        const res = await this.getDistrict(val)
        this.toDistricts = Object.values(res.data)
      }
    },
    post(post) {
      this.srcPreviews = post.postImage
    }
  },
  computed: {
    ...mapGetters({
      cities: 'post/getCity',
      itemTypes: 'item/itemTypes',
      trucks: 'driver/trucks'
    })
  },
  methods: {
    ...mapActions({
      getTrucksOfDriver: 'driver/getTrucksOfDriver',
      getItemTypes: 'item/fetchItemTypes',
      getCityName: 'post/getCityName',
      getDistrict: 'post/getDistrict'
    }),
    handleUploadImage(e) {
      if (e.target.files.length) {
        this.post.image = [...e.target.files]
        this.srcPreviews = [...e.target.files].map(img => URL.createObjectURL(img))
      }
    }
  },
  async created() {
    await this.getCityName()
    await this.getItemTypes()
    if (!this.trucks.length) await this.getTrucksOfDriver()
  }
}
</script>
