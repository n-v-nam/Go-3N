<template>
  <div id="choose-me" class="px-10">
    <vs-row>
      <vs-col vs-w="6">
        <img src="@/assets/img/user/home/home-1.png" atl="" class="p-16" />
      </vs-col>
      <vs-col vs-w="6">
        <div class="bg-gray-100 mt-24 py-6 px-10 rounded">
          <p class="titile font-oswald font-bold text-center text-4xl mb-10">
            <span class="text-red-600">-</span>
            Tạo sao bạn nên chọn <span class="text-red-600 font-oswald">GO.3N</span>
            <span class="text-red-600">-</span>
          </p>
          <p class="font-bold">Giao nhanh giá cả hợp lý</p>
          <p class="mb-5">Chọn <span class="text-red-600">GO.3N</span> – Bạn chọn dịch vụ hỗ trợ giao hàng chuyên nghiệp với giá hợp lý, vận chuyển bằng xe tải.</p>
          <p class="font-bold">Dịch vụ đa dạng</p>
          <p class="mb-5">Đáp ứng nhu cầu giao hàng ngay lập tức từ các hàng hóa nhỏ gọn đến vận chuyển hàng hóa, đồ đạc với kích thước lớn, cồng kềnh bốc xếp hay giao hàng nhiều điểm…</p>
          <p class="font-bold">Giao nhận dễ dàng</p>
          <p class="mb-5">Thao tác đơn giản: chỉ cần chọn điểm giao hàng, nhận hàng và dịch vụ đi kèm, hệ thống thông minh sẽ tự động định vị và kết nối bạn với tài xế phù hợp.</p>
        </div>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-w="6">
        <div class="mt-52 text-center rounded bg-gray-100 p-10">
          <p class="titile font-oswald font-bold text-center text-4xl mb-10">
            Trở thành đối tác
            <span class="text-red-600 font-oswald">tài xế GO.3N</span>
            thật dễ dàng
          </p>
          <p>Kiếm thêm thu nhập với việc trở thành tài xế của chúng tôi</p>
          <p>Đăng bài và chúng tôi sẽ đưa bạn đến với khách hàng</p>
          <span class="material-icons block mt-16 font-bold text-2xl text-red-600" style="font-size: 60px !important"> arrow_downward </span>
          <vs-button class="px-9 py-3 font-bold text-2xl mt-16" color="danger" @click="onRegister">Đăng kí ngay</vs-button>
        </div>
      </vs-col>
      <vs-col vs-w="6">
        <img src="@/assets/img/user/home/home-2.png" atl="" class="p-16" />
      </vs-col>
    </vs-row>
    <div class="active-form mb-10 bg-gray-50 rounded pb-10">
      <p class="titile font-oswald font-bold text-center text-4xl mt-20 pt-4 mb-10">
        <span class="text-red-600">-</span>
        Cách thức hoạt động của <span class="text-red-600 font-oswald">GO.3N</span>
        <span class="text-red-600">-</span>
      </p>
      <vs-row>
        <vs-col vs-w="3" class="text-center">
          <div class="flex justify-center">
            <img src="@/assets/img/user/home/booking.png" alt="" />
          </div>
          <p class="mt-3 text-xl">Đặt đơn hàng</p>
          <p class="mt-4">Tìm bài đăng hù hợp với bạn</p>
          <p>Sau đó có thể trao đổi và đặt tài xế</p>
        </vs-col>
        <vs-col vs-w="3" class="text-center">
          <div class="flex justify-center">
            <img src="@/assets/img/user/home/booked.png" alt="" />
          </div>
          <p class="mt-3 text-xl">Tài xế xác nhận đơn hàng</p>
          <p class="mt-4">Tài xế tiếp nhận đơn hàng</p>
          <p>Liên hệ khách hàng thông báo huỷ/nhận đơn</p>
        </vs-col>
        <vs-col vs-w="3" class="text-center">
          <div class="flex justify-center">
            <img src="@/assets/img/user/home/shipper.png" alt="" />
          </div>
          <p class="mt-3 text-xl">Giao và hoàn tất đơn hàng</p>
          <p class="mt-4">Di chuyển nhận đơn hàng</p>
          <p>Sau đó giao đến điểm giao hàng và kết thúc</p>
        </vs-col>
        <vs-col vs-w="3" class="text-center">
          <div class="flex justify-center">
            <img src="@/assets/img/user/home/credit.png" alt="" />
          </div>
          <p class="mt-3 text-xl">Thanh toán phí giao hàng</p>
          <p class="mt-4">Tài xế xác nhận giao hàng thành công</p>
          <p>Thực hiện thanh toán số tiền giao hàng</p>
        </vs-col>
      </vs-row>
    </div>
  </div>
</template>

<script>
import { CUSTOMER_TYPE } from '@/constants/customer'
export default {
  data() {
    return { customer: CUSTOMER_TYPE }
  },
  methods: {
    onRegister() {
      const isLoggedIn = this.$store.state.clientAuth.token || sessionStorage.getItem('token')
      if (isLoggedIn) {
        const profile = JSON.parse(sessionStorage.getItem('profile')) || this.$store.state.clientAuth.profile
        if (profile.customer_type == 0) {
          this.$store.dispatch('app/setErrorNotification', 'Bạn đã là tài xế !')
        } else {
          this.$router.push('/page/profile')
        }
      } else {
        this.$router.push('/register')
      }
    }
  }
}
</script>
