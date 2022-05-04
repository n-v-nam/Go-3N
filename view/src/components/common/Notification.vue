<template>
  <div class="notification-admin">
    <vs-dropdown color="danger" class="hover:text-red-600">
      <span class="material-icons cursor-pointer hover:text-red-600 mx-4"> notifications </span>
      <vs-dropdown-menu class="max-h-80 overflow-y-scroll pb-2 rounded">
        <div v-if="notifications.length">
          <vs-dropdown-item class="w-72 bg-gray-100" :class="{ 'bg-gray-50': notification.status }" v-for="(notification, index) in notifications" :key="index">
            <vs-row class="content">
              <vs-col vs-w="3">
                <img class="w-12 rounded-full" :src="notification.notification_avatar" alt="avt" />
              </vs-col>
              <vs-col vs-w="9">
                <p class="title leading-none tracking-tighter">{{ notification.title }}</p>
                <span class="content text-xs tracking-tighter font-light leading-none">{{ notification.content }}</span>
              </vs-col>
            </vs-row>
          </vs-dropdown-item>
        </div>
        <div class="px-2 py-1 text-sm" v-else>Chưa có thông báo mới nào</div>
      </vs-dropdown-menu>
    </vs-dropdown>
  </div>
</template>

<script>
export default {
  name: 'NotificationPage',
  props: {
    notifications: {
      type: Array,
      default: () => []
    }
  }
}
</script>
