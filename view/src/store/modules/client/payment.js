import paymentServices from '@/services/client/payment'

const actions = {
  loadingMoney(store, data) {
    return paymentServices.loadingMoney(data)
  }
}

export default {
  namespaced: true,
  actions
}
