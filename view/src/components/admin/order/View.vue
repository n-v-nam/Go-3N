<template>
  <div class="truck-detail grid grid-cols-2 gap-x-10 gap-y-3">
    <vs-input class="w-full" disabled v-model="order.customerName" label-placeholder="Tên người đặt" />
    <vs-input class="w-full" disabled v-model="order.customerPhone" label-placeholder="SĐT người đặt" />
    <vs-input class="w-full" disabled v-model="order.driverName" label-placeholder="Tên tài xế" />
    <vs-input class="w-full" disabled v-model="order.driverPhone" label-placeholder="SĐT tài xế" />
    <vs-input class="w-full" disabled v-model="order.bookTruckInformation.to_city" label-placeholder="Nơi lấy hàng" />
    <vs-input class="w-full" disabled v-model="order.bookTruckInformation.from_city" label-placeholder="Nơi trả hàng" />
    <vs-input class="w-full" disabled v-model="order.bookTruckInformation.category_truck" label-placeholder="Loại xe" />
    <vs-input class="w-full" disabled v-model="order.bookTruckInformation.price" label-placeholder="Giá" />
    <vs-select placeholder="VD: Xe 10 tấn" class="w-full" label="Trạng thái" disabled v-model="order.status">
      <vs-select-item :key="index" :value="index" :text="item" v-for="(item, index) in orderStatusText" />
    </vs-select>
    <div class="text-gray-600">
      <p>Thời gian hoàn thành</p>
      <input type="datetime-local" class="w-full border-2 rounded-md px-4 py-1" disabled v-model="order.completedAt" />
    </div>
    <br />
    <div class="mt-4 flex justify-end">
      <vs-button
        color="danger"
        icon="delete"
        v-if="order.orderInformationId"
        class="mx-4"
        @click="$emit('actionDelete')"
      >
        Xoá
      </vs-button>
      <vs-button color="lightgray" @click="$emit('clearEvent')">Thoát</vs-button>
    </div>
  </div>
</template>

<script>
import { orderStatusText } from '@/constants/reserves'

export default {
  props: {
    order: Object
  },
  data() {
    return { orderStatusText }
  }
}
</script>
