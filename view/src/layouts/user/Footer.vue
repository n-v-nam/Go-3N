<template>
  <div class="main-footer">
    <div class="footer-content w-full flex my-5 ml-10 flex-col md:flex-row lg:px-28 border-t-2 pt-4">
      <div class="introduction md:w-1/3 font-light text-sm w-3/4">
        <img src="@/assets/img/user/logo-header-dark.svg" alt="logo" class="h-20 mb-4" />
        <p>Đây là một sản phẩm hỗ trợ mọi người trong việc đặt xe vận chuyển một cách dễ dàng và tiện lợi</p>
        <p>Sản phẩm được phát triển bởi nhóm sinh viên Trường ĐH Công nghệ, ĐHQGHN</p>
        <div class="flex items-center my-6">
          <vs-input placeholder="Tìm kiếm trong trang" class="w-3/4 mr-2" v-model="searchFilter" />
          <vs-button color="danger" icon="search"></vs-button>
        </div>
      </div>
      <div class="contact md:w-1/3 md:ml-28 mt-10 w-3/4 ml-0">
        <p class="sub-title font-serif text-xl font-semibold pb-2 border-b-2 border-dashed">Liên hệ chúng tôi:</p>
        <div class="location flex my-4">
          <span class="material-icons mr-2"> location_on </span>
          <span>144 Xuân Thuỷ, Cầu Giấy, Hà Nội</span>
        </div>
        <div class="work-time flex my-4">
          <span class="material-icons mr-2"> watch_later </span>
          <span> 7:00am-9:30pm </span>
        </div>
        <div class="contact-phone flex my-4">
          <span class="material-icons mr-2"> call </span>
          <span> +84388430686 </span>
        </div>
        <div class="contact-email flex my-4">
          <span class="material-icons mr-2"> email </span>
          <span> namroreal@gmail.com </span>
        </div>
      </div>
      <div class="information md:w-1/3 md:ml-20 mt-10 w-3/4 ml-0">
        <p class="sub-title font-serif text-xl font-semibold pb-2 border-b-2 border-dashed">Thông tin thêm:</p>
        <div class="developer-infor mt-1">
          <vs-collapse-item>
            <div slot="header">Đội ngũ phát triển</div>
            <span class="font-bold">- Nguyễn Văn Nam</span> <br />
            <span class="font-bold">- Đỗ Văn Nam</span> <br />
            <span class="font-bold">- Nguyễn Văn Nam</span> <br />
            Sinh viên K63 của Trường ĐH Công nghệ, ĐHQGHN <br />
            Khoa: Hệ thống thông tin.
          </vs-collapse-item>
          <vs-collapse-item>
            <div slot="header">Hợp tác và phát triển</div>
            <span class="font-bold">Liên hệ theo email để trao đổi chi tiết</span>
          </vs-collapse-item>
          <vs-collapse-item>
            <div slot="header">Điều khoản và chính sách</div>
            Cùng với Điều khoản sử dụng, khi khách hàng tải và sử dụng dịch vụ của Go.3n, khách hàng đã hiểu và đồng ý sẽ thực hiện đúng các quy định về chính sách bản quyền và các quy định về quyền
            sở hữu tài sản sở hữu trí tuệ của Go.3n.
            <p class="font-bold">- CHÍNH SÁCH VỀ BẢN QUYỀN</p>
            <p class="font-bold">- THÔNG BÁO KHI VI PHẠM BẢN QUYỀN</p>
            <p class="font-bold">- ĐIỀU KHOẢN SỬ DỤNG</p>
            <p>Chi tiết xem <span class="text-red-600 font-bold cursor-pointer">tại đây !</span></p>
          </vs-collapse-item>
          <vs-collapse-item>
            <div slot="header">Câu hỏi thường gặp</div>
            <span class="font-bold">Đang cập nhật...</span>
            <br />
            Mọi thắc mắc xin gửi thư về email: <span class="text-red-600">namroreal@gmail.com</span> để được giải đáp
          </vs-collapse-item>
        </div>
      </div>
    </div>
    <div class="copyright h-8 bg-gray-800 px-4 flex items-center justify-between md:px-28">
      <span class="text-white">Copyright © 2022. NamNV </span>
      <span class="text-white">Cập nhật lần cuối: 4/2022 </span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'footer-main',
  data() {
    return {
      searchFilter: ''
    }
  },
  mounted() {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animation-from-center-top')
          observer.disconnect()
        }
      })
    })
    observer.observe(document.querySelector('.footer-content'))
  }
}
</script>
