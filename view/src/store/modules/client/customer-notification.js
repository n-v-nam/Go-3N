/** @format */

import notificationServices from '@/services/common/notification-services'

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
  async fetchNotifications({ commit }) {
    const res = await notificationServices.getNotifications()
    commit('SET_NOTIFICATIONS', res.data)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
