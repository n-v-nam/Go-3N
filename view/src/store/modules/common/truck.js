/** @format */

import truckServices from '@/services/common/truck'

const state = () => ({
  truck: [],
  cities: []
})

const getters = {
  trucks: (state) => state.truck,
  getCity: (state) => state.cities
}

const mutations = {
  SET_TRUCK(state, payload) {
    state.truck = payload
  },
  SET_CITIES(state, payload) {
    state.cities = payload
  }
}

const actions = {
  getTrucks(store, status) {
    return truckServices.getTrucks(status)
  },
  getTruck(store, truckId) {
    return truckServices.getTruck(truckId)
  },
  async getCityName({ commit }) {
    const res = await truckServices.getCityName()
    commit('SET_CITIES', res.data)
  },
  createTruck(store, truck) {
    return truckServices.createTruck(truck)
  },
  updateTruck(store, truck) {
    return truckServices.updateTruck(truck)
  },
  async deleteTruck({ dispatch }, truckId) {
    const res = await truckServices.deleteTruck(truckId)
    if (res) {
      dispatch('app/setSuccessNotification', 'Xoá xe thành công', { root: true })
    }
  },
  searchTruck(store, licensePlatesFilter) {
    return truckServices.searchTruck(licensePlatesFilter)
  },
  async approveTruck({ dispatch }, id) {
    const res = await truckServices.approveTruck(id)
    if (res) {
      dispatch('app/setSuccessNotification', 'Duyệt thành công, đang gửi thông báo đến tài xế', { root: true })
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
