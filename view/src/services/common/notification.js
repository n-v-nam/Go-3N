/** @format */

import axios from '@/axios'

export default {
  // ADMIN
  getNotificationsForAdmin() {
    return axios.get('api/personnel-notifications')
  },
  deleteNotificationsByAdmin(id) {
    return axios.get(`api/personnel-notifications/${id}`)
  },
  // CLIENT
  getNotificationsForClient() {
    return axios.get('api/customer-notification')
  },
  deleteNotificationsByClient(id) {
    return axios.get(`api/customer-notification/${id}`)
  }
}
