/** @format */

import notificationServices from '@/services/common/notification'

const state = () => ({
  notifications: [
    {
      title: 'Notifications',
      content: 'content 1',
      link: '/post'
    },
    {
      title: 'Notifications',
      content: 'content 1. Đây là thông báo liên quan đến đơn hàng XS-123. Vui lòng kiềm tra',
      link: '/post'
    },
    {
      title: 'Notifications',
      content: 'content 1. Đây là thông báo liên quan đến đơn hàng XS-123. Vui lòng kiềm tra',
      link: '/post'
    },
    {
      title: 'Notifications',
      content: 'content 1. Đây là thông báo liên quan đến đơn hàng XS-123. Vui lòng kiềm tra',
      link: '/post'
    },
    {
      title: 'Notifications',
      content: 'content 1. Đây là thông báo liên quan đến đơn hàng XS-123. Vui lòng kiềm tra',
      link: '/post'
    }
  ]
})

const getters = {
  getNotifications: (state) => state.notifications
}

const mutations = {
  SET_NOTIFICATIONS(state, payload) {
    state.notifications = payload
  }
}

const actions = {
  // ADMIN
  async fetchNotifications({ commit }) {
    const res = await notificationServices.getNotificationsForAdmin().catch(() => {
      return { data: [] }
    })
    commit('SET_NOTIFICATIONS', res.data)
  },
  deleteNotificationsByAdmin(commit, id) {
    return notificationServices.deleteNotificationsByAdmin(id)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
