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
  },
  async createUser(commit, user) {
    return await userServices.createUser(user)
  },
  async updateUser(commit, user) {
    return await userServices.updateUser(user)
  },
  async deleteUser(commit, userId) {
    return await userServices.deleteUser(userId)
  },
  async searchUser(commit, emailFilter) {
    return await userServices.searchUser(emailFilter)
  },
  async changePassword(commit, data) {
    return await userServices.changePassword(data)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
