import axios from '@/axios'

export default {
  getOrders() {
    return axios.get('/api/order')
  },
  getOrder(orderId) {
    return axios.get(`/api/order/${orderId}`)
  },
  updateOrder(data) {
    return axios.put(`/api/order/${data.order_id}`, data)
  },
  deleteOrder(orderId) {
    return axios.delete(`/api/order/${orderId}`)
  }
}
