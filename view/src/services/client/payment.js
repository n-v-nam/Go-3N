import axios from '@/axios'

export default {
  loadingMoney(data) {
    return axios.post('api/payment/add-money', data)
  }
}
