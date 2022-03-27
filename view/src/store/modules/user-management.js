/** @format */

import userServices from '@/services/user-services'

const state = () => ({
  user: []
})

const getters = {}

const mutations = {}

const actions = {
  async getUsers() {
    return await userServices.getUsers()
  },
  async getUser(commit, userId) {
    return await userServices.getUser(userId)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
