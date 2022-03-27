/** @format */
import router from '@/router'
import authService from '@/services/auth-services'

const state = () => ({
  token: null,
  userInfo: {}
})

const getters = {}

const mutations = {
  SET_TOKEN(state, token) {
    state.token = token
  }
}

const actions = {
  async login({ commit, dispatch }, data) {
    const response = await authService.login(data)
    if (response) {
      commit('SET_TOKEN', response.data.access_token)
      sessionStorage.setItem('token', response.data.access_token)
      dispatch('app/setSuccessNotification', 'Login success !', { root: true })
      router.push('/admin')
    } else {
      dispatch('app/setErrorNotification', 'Login error !', { root: true })
    }
  },
  async logout({ commit, dispatch }) {
    const res = await authService.logout()
    if (res) {
      sessionStorage.removeItem('token')
      commit('SET_TOKEN', null)
      dispatch('app/setSuccessNotification', 'Logout success !', { root: true })
      router.push('/login')
    } else {
      dispatch('app/setErrorNotification', 'Logout error !', { root: true })
    }
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
