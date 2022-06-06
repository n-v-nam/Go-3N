import axios from '@/axios'

export default {
  createReserse(postId) {
    return axios.get(`api/customer-book-truck/book-truck/${postId}`)
  },
  deleteReserve(orderId) {
    return axios.get(`api/customer-book-truck/cancel-order/${orderId}`)
  },
  getReserves(orderType) {
    return axios.get(`api/customer-book-truck/list-order/${orderType}`)
  },
  getReserve(orderId) {
    return axios.get(`api/customer-book-truck/view-order/${orderId}`)
  },
  acceptReserve(orderId) {
    return axios.get(`api/customer-book-truck/accept-customer-book-order/${orderId}`)
  },
  completeReserve(orderId) {
    return axios.get(`api/customer-book-truck/completed-order/${orderId}`)
  },
  reviewReserve(data) {
    return axios.post(`api/customer-book-truck/review-driver/${data.orderId}`, data)
  }
}
