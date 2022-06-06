<template>
  <div class="truck-detail">
    <vs-input v-if="truck.truckId" class="w-full mb-6" v-model="truck.truckId" disabled label-placeholder="Mã xe" />
    <vs-input class="w-full mb-6" v-model="truck.name" label-placeholder="Tên xe" />
    <vs-row>
      <vs-col vs-w="6">
        <vs-input
          class="mb-6"
          v-model="truck.weightItems"
          label="Tải trọng xe"
          placeholder="Giá trị khoảng 5-100 (tấn)"
        />
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-w="6">
        <vs-input class="mb-6" v-model="truck.licensePlates" label-placeholder="Biển số xe" />
      </vs-col>
      <vs-col vs-w="6">
        <vs-select placeholder="VD: Xe 10 tấn" label="Loại xe" v-model="truck.categoryTruckId" class="mb-4">
          <vs-select-item
            :key="index"
            :value="item.category_truck_id"
            :text="item.name"
            v-for="(item, index) in categoryTrucks"
          />
        </vs-select>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-w="6">
        <vs-input class="mb-6" v-model="truck.height" label="Chiều cao" placeholder="Giá trị khoảng 2-8 (mét)" />
      </vs-col>
      <vs-col vs-w="6">
        <vs-input class="mb-6" v-model="truck.weight" label="Cân nặng" placeholder="Giá trị khoảng 1-30 (tấn)" />
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-w="6">
        <vs-input class="mb-6" v-model="truck.length" label="Chiều dài" placeholder="Giá trị khoảng 5-50 (mét)" />
      </vs-col>
      <vs-col vs-w="6">
        <vs-input class="mb-6" v-model="truck.width" label="Chiều rộng" placeholder="Giá trị khoảng 1.5-5 (mét)" />
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-w="6">
        <vs-input class="mb-6" v-model="truck.locationCity" label-placeholder="Vị trí cập nhật cuối cùng" />
      </vs-col>
      <vs-col vs-w="6">
        <vs-input class="mb-6" v-model="truck.locationNowAt" label-placeholder="Thời gian cập nhật cuối cùng" />
      </vs-col>
    </vs-row>
    <div class="">
      <div class="img flex items-center">
        <img :src="srcPreview" class="rounded-full mb-4 w-20 h-20" />
      </div>
      <p class="font-thin italic text-red-600">Lưu ý: Ảnh đính kèm là bắt buộc để xác minh xe</p>
      <input type="file" class="hidden" ref="uploadImage" @change="handleUploadImage" />
      <vs-button size="small" icon="add_a_photo" color="gray" @click="$refs.uploadImage.click()">
        Thêm ảnh đính kèm
      </vs-button>
    </div>
    <div class="mt-4 flex justify-end">
      <vs-button color="success" icon="assignment" v-if="truck.truckId" @click="$emit('actionEdit')">Lưu</vs-button>
      <vs-button color="danger" icon="delete" v-if="truck.truckId" class="mx-4" @click="$emit('actionDelete')">
        Xoá
      </vs-button>
      <vs-button color="primary" icon="local_shipping" class="mr-2" v-else @click="$emit('actionCreate')">
        Tạo
      </vs-button>
      <vs-button color="lightgray" @click="$emit('clearEvent')">Thoát</vs-button>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  props: {
    truck: Object,
    owner: Object
  },
  data() {
    return {
      srcPreview: null
    }
  },
  computed: {
    ...mapGetters({
      categoryTrucks: 'categoryTruck/getCategoryTrucks'
    })
  },
  watch: {
    truck() {
      this.srcPreview = this.truck.licensePlatesImage
    }
  },
  methods: {
    ...mapActions({
      fetchCategoryTrucks: 'categoryTruck/fetchCategoryTrucks'
    }),
    handleUploadImage(e) {
      if (e.target.files[0]) {
        this.truck.licensePlatesImage = e.target.files[0]
        this.srcPreview = URL.createObjectURL(e.target.files[0])
      }
    }
  },
  async created() {
    await this.fetchCategoryTrucks()
  }
}
</script>
