/** @format */
// Truck of driver management

import driverService from '@/services/client/driver'

const state = () => ({
  trucksOfDriver: [],
  postsOfDriver: []
})

const getters = {
  trucks: state => state.trucksOfDriver,
  posts: state => state.postsOfDriver
}

const mutations = {
  SET_TRUCKS(state, payload) {
    state.trucksOfDriver = payload
  }
}

const actions = {
  async getTrucksOfDriver({ commit }) {
    const res = await driverService.getTrucksOfDriver()
    commit('SET_TRUCKS', res.data)
    return res
  },
  getTruckOfDriver(store, id) {
    return driverService.getTruckOfDriver(id)
  },
  // Customer register truck => admin approve
  createTruckByDriver(store, data) {
    return driverService.createTruckByDriver(data)
  },
  updateTruckByDriver(store, data) {
    return driverService.updateTruckByDriver(data)
  },
  deleteTruckByDriver(store, id) {
    return driverService.deleteTruckByDriver(id)
  },
  getPostsByDriver(commit, data) {
    return driverService.getPostsByDriver(data)
  },
  getPostByDriver(commit, data) {
    return driverService.getPostByDriver(data)
  },
  updatePostByDriver(commit, data) {
    return driverService.updatePostByDriver(data)
  },
  deletePostByDriver(commit, data) {
    return driverService.deletePostByDriver(data)
  },
  createPostByDriver(commit, data) {
    return driverService.createPostByDriver(data)
  },
  acceptReceiveItems(store, orderId) {
    return driverService.acceptReceiveItems(orderId)
  },
  getSuggestTruck(store, suggestTruckId) {
    return driverService.getSuggestTruck(suggestTruckId)
  },
  getListSuggestTruck() {
    return driverService.getListSuggestTruck()
  },
  getListOrder() {
    return driverService.getListOrder()
  },
  acceptSuggestTruck(store, suggestTruckId) {
    return driverService.acceptSuggestTruck(suggestTruckId)
  },
  getListOrderByTruck(store, truckId) {
    return driverService.getListOrderByTruck(truckId)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
