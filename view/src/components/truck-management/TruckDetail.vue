<template>
  <div class="truck-detail">
    <vs-input class="w-full mb-6" v-model="truck.truckId" disabled label-placeholder="Mã xe" />
    <vs-input class="w-full mb-6" v-model="owner.name" disabled label-placeholder="Chủ xe" />
    <vs-input class="w-full mb-6" disabled v-model="owner.phone" label-placeholder="Số điện thoại chủ xe" />
    <vs-input class="w-full mb-6" v-model="truck.name" label-placeholder="Tên xe" />
    <vs-select placeholder="VD: Xe 10 tấn" label="Loại xe" v-model="truck.categoryTruckId" class="mb-4 pr-2 w-full">
      <vs-select-item :key="index" :value="item.category_truck_id" :text="item.name" v-for="(item, index) in categoryTrucks" />
    </vs-select>
    <vs-input class="w-full mb-6" v-model="truck.licensePlates" label-placeholder="Biển số xe" />
    <vs-input class="w-full mb-6" v-model="truck.weight" label-placeholder="Cân nặng" />
    <vs-input class="w-full mb-6" v-model="truck.weightItems" label-placeholder="Cân nặng hàng hoá" />
    <vs-input class="w-full mb-6" v-model="truck.locationCity" label-placeholder="Vị trí cập nhật cuối cùng" />
    <vs-input class="w-full mb-6" v-model="truck.locationNowAt" label-placeholder="Thời gian cập nhật cuối cùng" />
    <vs-input class="w-full mb-6" v-model="truck.countOrder" label-placeholder="Số lượt đặt" />
    <vs-checkbox class="w-full mb-6 justify-start" v-model="truck.status">Trạng thái</vs-checkbox>
    <div class="mt-4 flex justify-end">
      <vs-button color="success" icon="assignment" v-if="truck.truckId" @click="$emit('actionEdit')">Lưu</vs-button>
      <vs-button color="danger" icon="delete" v-if="truck.truckId" class="mx-4" @click="$emit('actionDelete')">Xoá</vs-button>
      <vs-button color="primary" icon="person_add" class="mr-2" v-else @click="$emit('actionCreate')">Tạo</vs-button>
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
  computed: {
    ...mapGetters({
      categoryTrucks: 'categoryTruckManagement/getCategoryTrucks'
      // itemTypes: 'itemManagement/getItemTypes'
    })
  },
  methods: {
    ...mapActions({
      fetchCategoryTrucks: 'categoryTruckManagement/fetchCategoryTrucks'
      // fetchItemTypes: 'itemManagement/fetchItemTypes'
    })
  },
  async created() {
    await this.fetchCategoryTrucks()
    // await this.fetchItemTypes()
  }
}
</script>
