/** @format */

import truckServices from '@/services/common/truck'

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
  getTrucks(status) {
    truckServices.getTrucks(status)
  },
  getTruck(commit, truckId) {
    truckServices.getTruck(truckId)
  },
  async getCityName({ commit }) {
    const res = await truckServices.getCityName()
    commit('SET_CITIES', res.data)
  },
  createTruck(commit, truck) {
    return truckServices.createTruck(truck)
  },
  updateTruck(commit, truck) {
    return truckServices.updateTruck(truck)
  },
  deleteTruck(commit, truckId) {
    return truckServices.deleteTruck(truckId)
  },
  searchTruck(commit, licensePlatesFilter) {
    return truckServices.searchTruck(licensePlatesFilter)
  },
  async approveTruck({ dispatch }, id) {
    const res = await truckServices.approveTruck(id)
    if (res) {
      dispatch('app/setSuccessNotification', 'Duyệt thành công, đang gửi thông báo đến tài xế')
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
