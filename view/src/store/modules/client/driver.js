/** @format */
// Truck of driver management

import driverService from '@/services/client/driver'

const state = () => ({
  trucksOfDriver: [],
  postsOfDriver: []
})

const getters = {
  trucks: (state) => state.trucksOfDriver,
  posts: (state) => state.trucksOfDriver
}

const mutations = {
  SET_TRUCKS(state, payload) {
    state.trucksOfDriver = payload
  }
}

const actions = {
  getTrucksOfDriver() {
    return driverService.getTrucksOfDriver()
  },
  // Customer register truck => admin approve
  createTruckByDriver(dispatch, data) {
    return driverService.createTruckByDriver(data)
  },
  updateTruckByDriver(dispatch, data) {
    return driverService.updateTruckByDriver(data)
  },
  async deleteTruckByDriver({ dispatch }, id) {
    const res = await driverService.deleteTruckByDriver(id)
    if (res) dispatch('app/setSuccessNotification', 'Xoá thành công', { root: true })
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
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
