/** @format */

import truckServices from '@/services/truck-services'

const state = () => ({
  truck: []
})

const getters = {}

const mutations = {}

const actions = {
  async getTrucks() {
    return await truckServices.getTrucks()
  },
  async getTruck(commit, truckId) {
    return await truckServices.getTruck(truckId)
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
