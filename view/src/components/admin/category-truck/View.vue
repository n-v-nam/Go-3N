<template>
  <div class="truck-detail">
    <vs-input
      class="w-full mb-6"
      v-model="categoryTruck.categoryTruckId"
      disabled
      v-if="categoryTruck.categoryTruckId"
      label-placeholder="Mã loại xe"
    />
    <vs-input class="w-full mb-6" v-model="categoryTruck.name" label-placeholder="Tên loại xe" />
    <vs-input
      class="w-full mb-6"
      v-model="categoryTruck.slug"
      disabled
      v-if="categoryTruck.categoryTruckId"
      label-placeholder="slug"
    />
    <div class="mt-4 flex justify-end">
      <vs-button color="success" icon="assignment" v-if="categoryTruck.categoryTruckId" @click="$emit('actionEdit')">
        Lưu
      </vs-button>
      <vs-button
        color="danger"
        icon="delete"
        v-if="categoryTruck.categoryTruckId"
        class="mx-4"
        @click="$emit('actionDelete')"
      >
        Xoá
      </vs-button>
      <vs-button color="primary" icon="add" class="mr-2" v-else @click="$emit('actionCreate')">Tạo</vs-button>
      <vs-button color="lightgray" @click="$emit('clearEvent')">Thoát</vs-button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    categoryTruck: Object
  }
}
</script>
