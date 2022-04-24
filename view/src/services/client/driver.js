/** @format */

import axios from '@/axios'

export default {
  getTrucksOfDriver() {
    return axios.get('api/driver')
  },
  createTrucksByDriver(data) {
    return axios.post('api/driver', data)
  },
  updateTrucksByDriver(data) {
    return axios.put(`api/driver/${data.truck_id}`, data)
  },
  deleteTrucksByDriver(id) {
    return axios.delete(`api/driver/${id}`)
  },
  createPostByDriver(data) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    return axios.post(`api/driver-post`, data, config)
  }
}
