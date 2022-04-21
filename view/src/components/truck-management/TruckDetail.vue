<template>
  <div class="truck-detail">
    <vs-input class="w-full mb-6" v-model="truck.truckId" label-placeholder="Số xe" />
    <vs-input class="w-full mb-6" v-model="truck.truckName" label-placeholder="Tên xe" />
    <vs-select class="selectExample" label="Figuras" v-model="truck.select1">
      <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item, index) in options1" />
    </vs-select>
    <vs-input class="w-full mb-6" v-model="truck.licensePlates" label-placeholder="Biển số xe" />
    <vs-input class="w-full mb-6" v-model="truck.phone" label-placeholder="Số điện thoại" />
    <vs-input class="w-full mb-6" v-model="truck.weight" label-placeholder="Cân nặng" />
    <vs-input class="w-full mb-6" v-model="truck.weightItems" label-placeholder="Cân nặng hàng hoá" />
    <vs-input class="w-full mb-6" v-model="truck.locationNowCity" label-placeholder="Vị trí cập nhật cuối cùng" />
    <vs-input class="w-full mb-6" v-model="truck.locationNowAt" label-placeholder="Thời gian cập nhật cuối cùng" />
    <vs-input class="w-full mb-6" v-model="truck.countOrder" label-placeholder="Số lượt đặt" />
    <vs-checkbox class="w-full mb-6" v-model="truck.status">Trạng thái</vs-checkbox>
    <div class="mt-4 flex justify-end">
      <vs-button color="success" icon="assignment" v-if="truck.truckId" @click="$emit('actionEdit')">Lưu</vs-button>
      <vs-button color="danger" icon="delete" v-if="truck.truckId" class="mx-4" @click="$emit('actionDelete')">Xoá</vs-button>
      <vs-button color="primary" icon="person_add" class="mr-2" v-else @click="$emit('actionCreate')">Tạo</vs-button>
      <vs-button color="lightgray" @click="$emit('clearEvent')">Thoát</vs-button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    truck: Object
  }
}
</script>
