<template>
  <div class="messenger-components grid grid-cols-5 my-10 rounded-lg">
    <div class="users bg-gray-100 border-2 px-4">
      <vs-input
        size="small"
        placeholder="Tìm kiếm theo SĐT..."
        class="w-full mb-6 py-2 border-b-2"
        v-model="phoneFilter"
        @keypress.enter="onSearch"
      />
      <p
        v-for="(driver, index) in drivers"
        :key="index"
        class="my-2 font-bold flex items-center gap-2 px-2 py-2 rounded cursor-pointer"
        :class="{ 'bg-gray-200 pointer-events-none': partner.id == driver.id }"
        @click="partner = driver"
      >
        <img :src="driver.avatar" class="w-10 h-10 rounded-full" alt="avatar" />
        {{ driver.name }}
      </p>
      <p v-if="!drivers.length">
        Bạn chưa có tin nhắn với ai
        <br />
        Vui lòng nhập chính xác số điện thoại người dùng khác để bắt đầu chat với họ
      </p>
    </div>
    <div class="content col-start-2 col-end-6 bg-gray-100 overflow-y-scroll">
      <div class="header flex items-center justify-between border-b-2 px-2 py-4">
        <div class="flex items-center gap-4 font-bold">
          <img :src="partner.avatar" class="w-10 h-10 rounded-full" alt="avatar" />
          <div>
            <p>{{ partner.name }}</p>
            <p class="font-thin text-sm">{{ partner.phone }}</p>
          </div>
        </div>
        <vs-dropdown vs-custom-content vs-trigger-click color="danger">
          <span class="material-icons cursor-pointer hover:text-red-600">more_vert</span>
          <vs-dropdown-menu class="text-sm w-max">
            <vs-dropdown-item>Xoá tin nhắn</vs-dropdown-item>
            <vs-dropdown-item @click="$router.push(`/report?phone=${partner.phone.replace('+84', '0')}`)">
              Báo cáo người này
            </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>
      </div>
      <div class="messages h-96 relative overflow-scroll">
        <p v-for="(message, index) in messages" :key="index">
          <span class="absolute px-3 py-1 rounded" :class="message.css" :style="message.style">
            {{ message.content }}
          </span>
        </p>
      </div>
      <div class="actions flex items-center justify-center px-4 gap-4 py-2 border-t-2 rounded">
        <vs-input class="w-full" placeholder="Nhập tin nhắn..." @keypress.enter="onSend" v-model="message"></vs-input>
        <vs-button icon="send" color="danger" :disabled="!message" @click="onSend"></vs-button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'messenger-components',
  data() {
    return {
      drivers: [],
      messages: [],
      message: '',
      partner: {},
      phoneFilter: ''
    }
  },
  computed: {
    profile() {
      return JSON.parse(localStorage.getItem('profileClient')) || this.$store.state.clientAuth.profile
    }
  },
  watch: {
    partner(val) {
      if (val.id) this.getMessages()
    }
  },
  methods: {
    ...mapActions({
      setErrorNotification: 'app/setErrorNotification'
    }),
    onSend() {
      const { id } = this.profile
      this.$socket.emit('send-message', {
        sender: id,
        receiver: this.partner.id,
        content: this.message
      })
    },
    onSearch() {
      const { id } = this.profile
      if (this.phoneFilter) {
        let phone = this.phoneFilter.split('')
        if (phone[0] == 0) phone[0] = '+84'
        this.$socket.emit('search-customer', { phone: phone.join(''), id })
      }
    },
    getMessages() {
      this.message = ''
      const { id } = this.profile
      if (id && this.partner) this.$socket.emit('get-messages', { id, partner: this.partner.id })
    },
    getCustomers() {
      const { id } = this.profile
      if (id) this.$socket.emit('get-customers', { id })
    }
  },
  created() {
    this.getCustomers()
  },
  mounted() {
    const { id } = this.profile
    this.sockets.subscribe('get-messages', function (data) {
      this.messages = data.map((message, index) => {
        const style = `bottom: ${index * 40 + 10}px;`
        if (message.sender == id) {
          const css = `left-4 bg-blue-400`
          return {
            ...message,
            css,
            style
          }
        }
        const css = `right-4 bg-green-400`
        return {
          ...message,
          css,
          style
        }
      })
    })
    this.sockets.subscribe('get-customers', function (data) {
      this.drivers = data ?? []
      this.partner = this.drivers[0] ?? {}
    })
    this.sockets.subscribe('search-customer', function (customers) {
      if (customers) {
        if (customers.id === this.profile.id) {
          this.setErrorNotification('Người dùng này là bạn!')
        } else {
          this.partner = customers
          this.drivers = [customers]
        }
      } else {
        this.setErrorNotification('Không tìm thấy người dùng này !')
      }
    })
    this.sockets.subscribe('send-message', function ({ status }) {
      if (status) {
        this.getMessages()
      } else {
        this.setErrorNotification('Không gửi được tin nhắn, đã có lỗi xảy ra')
      }
    })
    this.sockets.subscribe('receive-message', function () {
      this.getMessages()
    })
    if (this.$route.query.phone) {
      this.phoneFilter = this.$route.query.phone
      this.onSearch()
    }
  },
  beforeDestroy() {
    this.sockets.unsubscribe('get-messages')
    this.sockets.unsubscribe('get-customers')
    this.sockets.unsubscribe('search-customer')
    this.sockets.unsubscribe('send-message')
    this.sockets.unsubscribe('receive-message')
  }
}
</script>
