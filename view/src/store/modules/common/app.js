/** @format */

const state = () => ({
  loading: false,
  silent: false,
  scroll: {
    scrollX: 0,
    scrollY: 0
  },
  notification: {}
})

const getters = {}

const mutations = {
  SET_LOADING(state, payload) {
    state.loading = payload
  },
  SET_SILENT(state, payload) {
    state.silent = payload
  },
  SET_NOTIFICATION(state, payload) {
    state.notification = payload
  },
  SET_SCROLL(state, payload) {
    state.scroll = payload
  }
}

const actions = {
  setLoading({ commit }, payload) {
    commit('SET_LOADING', payload)
  },
  setSilent({ commit }, payload) {
    commit('SET_SILENT', payload)
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
      type: 'danger',
      show: true
    }
    commit('SET_NOTIFICATION', notification)
  },
  setScroll({ commit }, payload) {
    commit('SET_SCROLL', payload)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
