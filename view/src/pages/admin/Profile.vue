<template>
  <div class="profile">
    <TitlePage title="Thông tin người dùng" icon="person" />
    <div class="user-profile m-8 flex items-center">
      <div class="w-1/5 flex flex-col justify-center">
        <img
          :src="srcPreviewAvatar || userProfile.avatar || require('@/assets/img/noentry.png')"
          class="rounded-full mb-4 w-52 h-52"
        />
        <input type="file" class="hidden" ref="updateAvatar" @change="handleUpdateAvatar" />
        <vs-button
          v-show="isChangeProfile"
          size="small"
          icon="add_a_photo"
          color="gray"
          @click="$refs.updateAvatar.click()"
          >Cập nhật ảnh đại diện</vs-button
        >
      </div>
      <div class="basic-info ml-20 border-l-2 p-4">
        <span v-if="!isChangeProfile" class="text-4xl font-bold block">{{ userProfile.name }}</span>
        <vs-input v-else v-model="userProfile.name" class="text-4xl font-bold block" />
        <span class="font-medium block"
          >Chức danh: <span class="font-light mx-2">{{ userProfile.type | userRole }}</span></span
        >
        <span class="font-medium"
          >Email: <span class="font-light mx-2">{{ userProfile.email }}</span></span
        >
        <div class="chang-password mt-4">
          <vs-button size="small" class="mr-2" @click="onChangePassword">Thay đổi mật khẩu</vs-button>
          <vs-button v-if="!isChangeProfile" size="small" color="danger" class="mx-2" @click="isChangeProfile = true"
            >Thay đổi thông tin</vs-button
          >
          <div v-else class="inline">
            <vs-button size="small" color="success" class="mx-2" @click="onChangeProfile">Lưu thay đổi</vs-button>
            <vs-button size="small" color="gray" class="mx-2" @click="clearEvent">Huỷ</vs-button>
          </div>
        </div>
      </div>
    </div>
    <vs-popup
      class="dialog-change-password"
      title="Thay đổi mật khẩu tài khoản"
      :active.sync="isChangePassword"
      button-close-hidden
    >
      <vs-input
        type="password"
        class="w-full"
        v-validate="'required|min:8'"
        label-placeholder="Mật khẩu hiện tại"
        data-vv-as="Mật khẩu hiện tại"
        name="currentPassword"
        v-model="currentPassword"
      />
      <span v-show="onSubmit" class="text-red-600 text-xs mx-2">{{ errors.first('currentPassword') }}</span>
      <vs-input
        type="password"
        class="w-full mt-6"
        v-validate="'required|min:8'"
        label-placeholder="Mật khẩu mới"
        data-vv-as="Mật khẩu mới"
        name="newPassword"
        v-model="newPassword"
      />
      <span v-show="onSubmit" class="text-red-600 text-xs mx-2">{{ errors.first('newPassword') }}</span>
      <vs-input
        type="password"
        class="w-full mt-6"
        v-validate="'required'"
        label-placeholder="Nhập lại mật khẩu mới"
        data-vv-as="Nhập lại mật khẩu mới"
        name="reNewPassword"
        v-model="reNewPassword"
      />
      <span v-show="onSubmit" class="text-red-600 text-xs mx-2">{{ errors.first('reNewPassword') }}</span>
      <div class="bnt-action mt-6 text-right">
        <vs-button :disabled="!isValidate && onSubmit" class="normal mx-2" @click="actionChangePassword"
          >Xác nhận</vs-button
        >
        <vs-button class="normal mx-2" @click="clearEvent">Huỷ</vs-button>
      </div>
    </vs-popup>
  </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  name: 'admin-profile',
  data() {
    return {
      isChangePassword: false,
      isChangeProfile: false,
      onSubmit: false,
      currentPassword: '',
      newPassword: '',
      reNewPassword: '',
      srcPreviewAvatar: null
    }
  },
  computed: {
    userProfile() {
      return this.profile()
    },
    isValidate() {
      return !this.errors.any()
    }
  },
  methods: {
    ...mapGetters({
      profile: 'auth/profile'
    }),
    ...mapActions({
      getProfile: 'auth/getProfile',
      updateProfile: 'auth/updateProfile',
      changePassword: 'user/changePassword',
      setErrorNotification: 'app/setErrorNotification',
      setSuccessNotification: 'app/setSuccessNotification'
    }),
    onChangePassword() {
      this.isChangePassword = true
      this.onSubmit = false
      this.currentPassword = ''
      this.newPassword = ''
      this.reNewPassword = ''
    },
    async actionChangePassword() {
      if (!this.onSubmit && this.isValidate) {
        if (this.newPassword !== this.reNewPassword) {
          this.setErrorNotification('Nhập lại mật khẩu mới chưa trùng khớp !')
          return
        }
        const res = await this.changePassword({ passwordOld: this.currentPassword, passwordNew: this.newPassword })
        if (res) {
          this.setSuccessNotification('Thay đổi mật khẩu thành công !')
          this.clearEvent()
        }
      } else if (this.onSubmit) {
        if (this.newPassword !== this.reNewPassword) {
          this.setErrorNotification('Nhập lại mật khẩu mới chưa trùng khớp !')
          return
        }
        const res = await this.changePassword({ passwordOld: this.currentPassword, passwordNew: this.newPassword })
        if (res) {
          this.setSuccessNotification('Thay đổi mật khẩu thành công !')
          this.clearEvent()
        }
      } else this.onSubmit = true
    },
    handleUpdateAvatar(e) {
      if (e.target.files[0]) {
        this.userProfile.avatar = e.target.files[0]
        this.srcPreviewAvatar = URL.createObjectURL(e.target.files[0])
      }
    },
    async onChangeProfile() {
      let formData = new FormData()
      formData.append('avatar', this.userProfile.avatar)
      formData.append('name', this.userProfile.name)
      formData.append('type', this.userProfile.type)
      await this.updateProfile(formData)
      await this.getProfile()
      this.clearEvent()
    },
    clearEvent() {
      this.isChangePassword = false
      this.isChangeProfile = false
      this.onSubmit = false
      this.currentPassword = ''
      this.newPassword = ''
      this.reNewPassword = ''
      this.srcPreviewAvatar = null
    }
  },
  async created() {
    await this.getProfile()
    console.log(this.userProfile)
  }
}
</script>
