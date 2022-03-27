/** @format */

const state = () => ({
  loading: false,
  notification: {}
})

const getters = {}

const mutations = {
  SET_LOADING(state, payload) {
    state.loading = payload
  },
  SET_NOTIFICATION(state, payload) {
    state.notification = payload
  }
}

const actions = {
  setLoading({ commit }, payload) {
    commit('SET_LOADING', payload)
  },
  setSuccessNotification({ commit }, payload) {
    const notification = {
      message: payload,
      type: 'success',
      show: true
    }
    commit('SET_NOTIFICATION', notification)
  },
  setErrorNotification({ commit }, payload) {
    const notification = {
      message: payload,
      type: 'error',
      show: true
    }
    commit('SET_NOTIFICATION', notification)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
