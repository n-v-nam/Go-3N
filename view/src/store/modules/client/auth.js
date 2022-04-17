/** @format */
import router from '@/router'
import authService from '@/services/client/auth-services'

const state = () => ({
  token: null,
  profile: {}
})

const getters = {
  profile: (state) => state.profile
}

const mutations = {
  SET_TOKEN(state, token) {
    state.token = token
  },
  SET_PROFILE(state, profile) {
    state.profile = profile
  }
}

const actions = {
  async login({ dispatch }, data) {
    const response = await authService.login(data)
    if (response) {
      dispatch('setToken', response.data)
      dispatch('app/setSuccessNotification', 'Đăng nhập thành công !', { root: true })
      router.push('/home')
    }
  },
  async getProfile({ commit }) {
    const response = await authService.getProfile()
    if (response) {
      commit('SET_PROFILE', response.data)
      sessionStorage.setItem('profile', JSON.stringify(response.data))
      return true
    } else {
      router.push('/login')
    }
  },
  async updateProfile(commit, data) {
    await authService.updateProfile(data)
  },
  async logout({ dispatch }) {
    const res = await authService.logout()
    if (res) {
      dispatch('setToken')
      dispatch('app/setSuccessNotification', 'Đăng xuất thành công !', { root: true })
      router.push('/login')
    }
  },
  async changePassword({ dispatch }, data) {
    const res = await authService.changePassword(data)
    if (res) {
      dispatch('app/setSuccessNotification', 'Đổi mật khẩu thành công !', { root: true })
      dispatch('setToken')
      router.push('/login')
    }
  },
  setToken({ commit }, data = undefined) {
    if (!data) {
      commit('SET_TOKEN', null)
      commit('SET_PROFILE', {})
      sessionStorage.removeItem('token')
      sessionStorage.removeItem('profile')
    } else {
      commit('SET_TOKEN', data.token.access_token)
      commit('SET_PROFILE', data.customer_information)
      sessionStorage.setItem('token', data.token.access_token)
      sessionStorage.setItem('profile', JSON.stringify(data.customer_information))
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
