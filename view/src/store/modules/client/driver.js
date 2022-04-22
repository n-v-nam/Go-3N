/** @format */
// Truck of driver management

import driverService from '@/services/client/driver'

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
  getTrucksOfDriver() {
    return driverService.getTrucksOfDriver()
  },
  // Customer register truck => customer approve
  createTruckByDriver(dispatch, data) {
    return driverService.createTruckByDriver(data)
  },
  updateTruckByDriver(dispatch, data) {
    return driverService.updateTruckByDriver(data)
  },
  async deleteTruckByDriver({ dispatch }, id) {
    const res = await driverService.deleteTruckByDriver(id)
    if (res) dispatch('app/setSuccessNotification', 'Xoá thành công', { root: true })
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
