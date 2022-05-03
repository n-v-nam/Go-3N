import axios from '@/axios'

export default {
  loadingMoney(data) {
    return axios.post('api/payment/add-money', data)
  },
  saveBill(data) {
    return axios.post('api/client-customer/save-bill', data)
  }
}
