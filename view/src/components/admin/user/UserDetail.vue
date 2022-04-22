<!-- @format -->

<template>
  <div class="user-detail">
    <vs-input class="mb-2 w-1/5" label="ID" placeholder="id" v-if="user.id" :value="user.id" disabled />
    <vs-input class="mb-2 w-full" label="Tên" placeholder="Tên" v-model="user.name" />
    <vs-input class="mb-4 w-full" label="Email" placeholder="Email" v-model="user.email" />
    <vs-input class="mb-4 w-full" label="Mật khẩu" placeholder="Mật khẩu" v-if="!user.id" v-model="user.password" />
    <vs-row>
      <label>Chức danh:</label>
      <vs-radio
        class="mx-2"
        v-for="(name, index) of userType"
        :key="index"
        v-model="user.type"
        :vs-name="index"
        :vs-value="index"
        >{{ name }}</vs-radio
      >
    </vs-row>
    <div class="mt-4 flex justify-end">
      <vs-button color="success" icon="assignment" v-if="user.id" @click="$emit('actionEdit')">Lưu</vs-button>
      <vs-button color="danger" icon="delete" v-if="user.id" class="mx-4" @click="$emit('actionDelete')">Xoá</vs-button>
      <vs-button color="primary" icon="person_add" class="mr-2" v-else @click="$emit('actionCreate')">Tạo</vs-button>
      <vs-button color="lightgray" @click="$emit('clearEvent')">Thoát</vs-button>
    </div>
  </div>
</template>

<script>
import { USER_TYPE } from '@/constants/user'
export default {
  name: 'user-detail',
  props: {
    user: Object
  },
  data() {
    return {
      userType: USER_TYPE
    }
  }
}
</script>
