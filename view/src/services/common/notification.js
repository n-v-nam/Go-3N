/** @format */

import axios from '@/axios'

export default {
  // ADMIN
  getNotificationsForaAdmin() {
    return axios.get('api/personnel-notifications')
  },
  deleteNotificationsByAdmin(id) {
    return axios.get(`api/personnel-notifications/${id}`)
  }
}
