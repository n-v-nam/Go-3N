<!-- @format -->

<template>
  <div class="customer-detail">
    <vs-row class="flex items-end">
      <input type="file" class="hidden" ref="uploadAvatar" @change="handleUploadAvatar" />
      <div class="avatar w-full flex flex-col items-center justify-center">
        <vs-tooltip text="Bấm để thay đổi ảnh">
          <img
            class="w-36 mb-2 rounded-full cursor-pointer"
            :src="srcPreviewAvatar || customer.avatar"
            @click="$refs.uploadAvatar.click()"
          />
        </vs-tooltip>
        <vs-button icon="add_a_photo" size="normal" class="mb-2" @click="$refs.uploadAvatar.click()">
          {{ srcPreviewAvatar ? 'Thay đổi ảnh khác' : 'Tải ảnh đại diện' }}</vs-button
        >
      </div>
    </vs-row>
    <vs-input class="mb-2 w-1/6" label="ID" placeholder="id" v-if="customer.id" :value="customer.id" disabled />
    <vs-input class="mb-3 w-full" label="Tên" placeholder="Tên" v-model="customer.name" />
    <vs-row>
      <label class="ml-1">Giới tính:</label>
      <vs-radio
        class="mx-2"
        v-for="(name, index) of sexType"
        :key="index"
        v-model="customer.sex"
        vs-name="customer.sex"
        :vs-value="index"
        >{{ name }}</vs-radio
      >
    </vs-row>
    <vs-input class="mb-4 mt-2 w-full" label="Số điện thoại" placeholder="Số điện thoại" v-model="customer.phone" />
    <vs-checkbox class="mb-4" v-if="customer.id" v-model="customer.is_verified" disabled>Đã xác thực</vs-checkbox>
    <vs-input
      class="mb-4 w-full"
      label="Mật khẩu"
      placeholder="Mật khẩu"
      v-if="!customer.id"
      v-model="customer.password"
    />
    <vs-row>
      <label class="ml-1">Loại khác hàng:</label>
      <vs-radio
        class="mx-2"
        v-for="(name, index) of customerType"
        :key="index"
        v-model="customer.customer_type"
        vs-name="customer.customer_type"
        :vs-value="index"
        >{{ name }}</vs-radio
      >
    </vs-row>
    <div class="mt-4 flex justify-end">
      <vs-button color="success" icon="assignment" v-if="customer.id" @click="$emit('actionEdit')">Lưu</vs-button>
      <vs-button color="danger" icon="delete" v-if="customer.id" class="mx-4" @click="$emit('actionDelete')"
        >Xoá</vs-button
      >
      <vs-button color="primary" icon="person_add" class="mr-2" v-else @click="$emit('actionCreate')">Tạo</vs-button>
      <vs-button color="lightgray" @click="$emit('clearEvent')">Thoát</vs-button>
    </div>
  </div>
</template>

<script>
import { CUSTOMER_TYPE, SEX_TYPE } from '@/constants/customer'
export default {
  name: 'customer-detail',
  props: {
    customer: Object
  },
  data() {
    return {
      customerType: CUSTOMER_TYPE,
      sexType: SEX_TYPE,
      srcPreviewAvatar: null
    }
  },
  methods: {
    handleUploadAvatar(e) {
      if (e.target.files[0]) {
        this.customer.avatar = e.target.files[0]
        this.srcPreviewAvatar = URL.createObjectURL(e.target.files[0])
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.con-vs-checkbox {
  justify-content: flex-start;
}
</style>
