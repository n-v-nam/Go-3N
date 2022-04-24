<template>
  <div id="client-profile" class="pt-10 pb-20 m-10">
    <div class="header flex">
      <vs-icon class="text-2xl mr-2" icon="arrow_right"></vs-icon>
      <p class="font-bold text-2xl mb-4">Thông tin chung:</p>
    </div>
    <div class="user-profile bg-gray-100 flex items-center">
      <div class="w-1/5 flex flex-col justify-center">
        <img :src="srcPreviewAvatar || userProfile.avatar || require('@/assets/img/noentry.png')" class="rounded-full mb-4 w-52 h-52" />
        <input type="file" class="hidden" ref="updateAvatar" @change="handleUpdateAvatar" />
        <vs-button v-show="isChangeProfile" size="small" icon="add_a_photo" color="gray" @click="$refs.updateAvatar.click()">Cập nhật ảnh đại diện</vs-button>
      </div>
      <div class="basic-info ml-20 border-l-2 p-4">
        <span v-if="!isChangeProfile" class="text-4xl font-bold block"
          >{{ userProfile.name }}
          <vs-tooltip :text="userProfile.phone_verified_at ? 'Tài khoản đã xác thực' : 'Bạn chưa xác thực tài khoản'" class="inline" position="right">
            <span v-if="userProfile.phone_verified_at" class="material-icons text-green-500"> verified </span>
            <span v-else class="material-icons"> unpublished </span>
          </vs-tooltip></span
        >
        <vs-input v-else v-model="userProfile.name" class="text-4xl font-bold block w-full" />
        <div class="my-3">
          <span class="font-medium">Giới tính:</span>
          <span v-if="!isChangeProfile" class="font-light mx-2">{{ userProfile.sex | sexType }}</span>
          <vs-select class="inline-block mx-2" v-else v-model="userProfile.sex">
            <vs-select-item :key="index" :value="item.value" :text="item.name" v-for="(item, index) in sexTypes" />
          </vs-select>
        </div>
        <span class="font-medium block"
          >Chức danh: <span class="font-light mx-2">{{ userProfile.customer_type | customerType }}</span></span
        >
        <span class="font-medium block"
          >Số điện thoại: <span class="font-light mx-2">{{ userProfile.phone }}</span></span
        >
        <span class="font-medium" v-if="userProfile.email"
          >Email: <span class="font-light ml-2">{{ userProfile.email }}</span>
          <span v-if="userProfile.email_verified_at" class="material-icons text-lg text-green-500"> verified </span>
          <span v-else class="material-icons text-lg"> unpublished </span>
        </span>
        <div class="chang-password mt-4">
          <vs-button size="small" class="mr-2" @click="onChangePassword">Thay đổi mật khẩu</vs-button>
          <vs-button v-if="!isChangeProfile" size="small" color="danger" class="mx-2" @click="isChangeProfile = true">Thay đổi thông tin</vs-button>
          <div v-else class="inline">
            <vs-button size="small" color="success" class="mx-2" @click="onChangeProfile">Lưu thay đổi</vs-button>
            <vs-button size="small" color="gray" class="mx-2" @click="clearEvent">Huỷ</vs-button>
          </div>
        </div>
      </div>
    </div>
    <div class="header flex mt-10">
      <vs-icon class="text-2xl mr-2" icon="arrow_right"></vs-icon>
      <p class="font-bold text-2xl">Thông tin dành riêng cho khách hàng:</p>
    </div>
    <div class="mt-2" v-if="!userProfile.email">
      <p class="font-light italic">
        Thêm địa chỉ email của bạn vào để được hỗ trợ tính năng thông báo
        <span class="text-red-600">miễn phí !</span>
      </p>
      <div class="flex justify-between w-1/2">
        <input v-model="email" class="bg-red-50 px-5 rounded w-3/4" placeholder="Nhập địa chỉ email" />
        <vs-button color="danger" icon="mail" @click="onAddEmail">Thêm</vs-button>
      </div>
    </div>
    <div class="mt-2" v-if="userProfile.email && !userProfile.email_verified_at">
      <p class="font-light italic">
        Bạn đã thêm email nhưng chưa xác thực vui lòng kiểm tra email đã đăng kí để xác thực để nhận
        <span class="text-red-600">ưu đãi!</span>
      </p>
    </div>
    <vs-popup class="dialog-change-password" title="Thay đổi mật khẩu tài khoản" :active.sync="isChangePassword" button-close-hidden>
      <vs-input type="password" class="w-full" v-validate="'required|min:8'" label-placeholder="Mật khẩu hiện tại" data-vv-as="Mật khẩu hiện tại" name="currentPassword" v-model="currentPassword" />
      <span v-show="onSubmit" class="text-red-600 text-xs mx-2">{{ errors.first('currentPassword') }}</span>
      <vs-input type="password" class="w-full mt-6" v-validate="'required|min:8'" label-placeholder="Mật khẩu mới" data-vv-as="Mật khẩu mới" name="newPassword" v-model="newPassword" />
      <span v-show="onSubmit" class="text-red-600 text-xs mx-2">{{ errors.first('newPassword') }}</span>
      <vs-input type="password" class="w-full mt-6" v-validate="'required'" label-placeholder="Nhập lại mật khẩu mới" data-vv-as="Nhập lại mật khẩu mới" name="reNewPassword" v-model="reNewPassword" />
      <span v-show="onSubmit" class="text-red-600 text-xs mx-2">{{ errors.first('reNewPassword') }}</span>
      <div class="bnt-action mt-6 text-right">
        <vs-button :disabled="!isValidate && onSubmit" class="normal mx-2" @click="actionChangePassword">Xác nhận</vs-button>
        <vs-button class="normal mx-2" @click="clearEvent">Huỷ</vs-button>
      </div>
    </vs-popup>
  </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  name: 'client-profile',
  data() {
    return {
      isChangePassword: false,
      isChangeProfile: false,
      onSubmit: false,
      currentPassword: '',
      newPassword: '',
      reNewPassword: '',
      srcPreviewAvatar: null,
      sexTypes: [
        { value: 1, name: 'Nam' },
        { value: 0, name: 'Nữ' },
        { value: 2, name: 'Khác' }
      ],
      email: ''
    }
  },
  computed: {
    userProfile() {
      return this.profile() || JSON.parse(localStorage.getItem('profileClient'))
    },
    isValidate() {
      return !this.errors.any()
    }
  },
  methods: {
    ...mapGetters({
      profile: 'clientAuth/profile'
    }),
    ...mapActions({
      getProfile: 'clientAuth/getProfile',
      updateProfile: 'clientAuth/updateProfile',
      changePassword: 'clientAuth/changePassword',
      setEmailCustomer: 'clientAuth/setEmailCustomer',
      confirmSetEmailCustomer: 'clientAuth/confirmSetEmailCustomer',
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
        const res = await this.changePassword({ oldPassword: this.currentPassword, newPassword: this.newPassword })
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
      formData.append('sex', this.userProfile.sex)
      await this.updateProfile(formData)
      this.clearEvent()
    },
    async onAddEmail() {
      const res = await this.setEmailCustomer({ email: this.email })
      if (res) {
        this.setSuccessNotification(res.message)
      }
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
    const profile = await this.getProfile()
    if (!profile) this.$router.push('/login')
    if (this.$route.query.token) {
      const token = this.$route.query.token
      const res = await this.confirmSetEmailCustomer(token).catch(() => false)
      if (res) {
        this.setSuccessNotification(res.message)
      }
      this.$router.push('/page/profile')
    }
  }
}
</script>
