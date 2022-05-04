<template>
  <div class="truck-detail">
    <vs-input
      class="w-full mb-6"
      v-model="itemType.itemTypeId"
      disabled
      v-if="itemType.itemTypeId"
      label-placeholder="Mã loại hàng"
    />
    <vs-input class="w-full mb-6" v-model="itemType.name" label-placeholder="Tên loại hàng" />
    <vs-input
      class="w-full mb-6"
      v-model="itemType.slug"
      v-if="itemType.itemTypeId"
      disabled
      label-placeholder="slug"
    />
    <div class="mt-4 flex justify-end">
      <vs-button color="success" icon="assignment" v-if="itemType.itemTypeId" @click="$emit('actionEdit')">
        Lưu
      </vs-button>
      <vs-button color="danger" icon="delete" v-if="itemType.itemTypeId" class="mx-4" @click="$emit('actionDelete')">
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
    itemType: Object
  }
}
</script>
