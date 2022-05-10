<template>
  <div
    class="fixed cursor-pointer hover:opacity-70 flex items-center justify-center bottom-5 w-10 h-10 z-10 right-24 bg-red-600 rounded-full drop-shadow-container"
  >
    <span class="material-icons text-red-60 text-white">forum</span>
  </div>
</template>

<script>
export default {
  name: 'chat-component',
  data() {
    return {}
  },
  mounted() {
    this.sockets.subscribe('send-message', function (val) {
      if (Notification.permission === 'granted') {
        const options = {
          body: val.message,
          dir: 'ltr',
          silent: true
        }
        new Notification(val.title + ' you', options)
      }
    })
  },
  created() {
    this.$socket.emit('connected', 2)
  }
}
</script>
