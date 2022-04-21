/** @format */

import truckServices from '@/services/common/truck-services'

const state = () => ({
  truck: [],
  cities: []
})

const getters = {
  getCity: (state) => state.cities
}

const mutations = {
  SET_CITIES(state, payload) {
    state.cities = payload
  }
}

const actions = {
  async getTrucks(status) {
    return await truckServices.getTrucks(status)
  },
  async getTruck(commit, truckId) {
    return await truckServices.getTruck(truckId)
  },
  async getCityName({ commit }) {
    const res = await truckServices.getCityName()
    commit('SET_CITIES', res.data)
  },
  async createTruck(commit, truck) {
    return await truckServices.createTruck(truck)
  },
  async updateTruck(commit, truck) {
    return await truckServices.updateTruck(truck)
  },
  async deleteTruck(commit, truckId) {
    return await truckServices.deleteTruck(truckId)
  },
  async searchTruck(commit, licensePlatesFilter) {
    return await truckServices.searchTruck(licensePlatesFilter)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
