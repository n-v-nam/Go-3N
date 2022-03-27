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
      dispatch('app/setSuccessNotification', 'Đăng nhập thành công !', { root: true })
      router.push('/admin')
    }
  },
  async logout({ commit, dispatch }) {
    const res = await authService.logout()
    if (res) {
      sessionStorage.removeItem('token')
      commit('SET_TOKEN', null)
      dispatch('app/setSuccessNotification', 'Đăng xuất thành công !', { root: true })
      router.push('/login')
    }
  },
  async resetPassword({ dispatch }, email) {
    const res = await authService.resetPassword(email)
    if (res) dispatch('app/setSuccessNotification', 'Đã gửi thành công !', { root: true })
  },
  async changePassword({ dispatch }, data) {
    const res = await authService.changePassword(data)
    if (res) {
      dispatch('app/setSuccessNotification', 'Đổi mật khẩu thành công !', { root: true })
      router.push('/login')
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
