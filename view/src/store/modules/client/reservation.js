import reservationServices from '@/services/client/reservation'

const state = () => ({})

const getters = {}

const mutations = {}

const actions = {
  createReserse(store, postId) {
    return reservationServices.createReserse(postId)
  },
  deleteReserve(store, orderId) {
    return reservationServices.deleteReserve(orderId)
  },
  getReserves() {
    return reservationServices.getReserves()
  },
  getReserve(store, orderId) {
    return reservationServices.getReserve(orderId)
  },
  acceptReserve(store, orderId) {
    return reservationServices.acceptReserve(orderId)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
