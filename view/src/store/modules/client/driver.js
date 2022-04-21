/** @format */

import driverService from '@/services/client/driver-services'

const state = () => ({
  trucksOfDriver: []
})

const getters = {
  getTruck: (state) => state.trucksOfDriver
}

const mutations = {
  SET_TRUCKS(state, payload) {
    state.trucksOfDriver = payload
  }
}

const actions = {
  async getTrucksOfDriver() {
    return await driverService.getTrucksOfDriver()
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
