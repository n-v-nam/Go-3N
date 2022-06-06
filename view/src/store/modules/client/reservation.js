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
  getReserves(store, orderType) {
    return reservationServices.getReserves(orderType)
  },
  getReserve(store, orderId) {
    return reservationServices.getReserve(orderId)
  },
  acceptReserve(store, orderId) {
    return reservationServices.acceptReserve(orderId)
  },
  completeReserve(store, orderId) {
    return reservationServices.completeReserve(orderId)
  },
  reviewReserve(store, data) {
    return reservationServices.reviewReserve(data)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
