/** @format */

import notificationServices from '@/services/common/notification'

const state = () => ({
  admin: [],
  client: []
})

const getters = {
  admin: state => state.admin,
  client: state => state.client
}

const mutations = {
  SET_NOTIFICATIONS_ADMIN(state, payload) {
    state.admin = payload
  },
  SET_NOTIFICATIONS_CLIENT(state, payload) {
    state.client = payload
  }
}

const actions = {
  // ADMIN
  async getNotificationsForAdmin({ commit }) {
    const res = await notificationServices.getNotificationsForAdmin().catch(() => {
      return { data: [] }
    })
    commit('SET_NOTIFICATIONS_ADMIN', res.data)
  },
  deleteNotificationsByAdmin(store, id) {
    return notificationServices.deleteNotificationsByAdmin(id)
  },
  async getNotificationsForClient({ commit }) {
    const res = await notificationServices.getNotificationsForClient().catch(() => {
      return { data: [] }
    })
    commit('SET_NOTIFICATIONS_CLIENT', res.data)
  },
  deleteNotificationsByClient(store, id) {
    return notificationServices.deleteNotificationsByClient(id)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
