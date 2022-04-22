/** @format */

import axios from '@/axios'

export default {
  getTrucks(data) {
    return axios.get(`api/truck/list-truck/${data.status}`)
  },
  getTruck(truckId) {
    return axios.get(`api/truck/${truckId}`)
  },
  getCityName() {
    return axios.get(`api/truck/get-city-name`)
  },
  updateTruck(truck) {
    return axios.put(`api/truck/${truck.truck_id}`, truck)
  },
  createTruck(truck) {
    return axios.post('api/truck', truck)
  },
  deleteTruck(truckId) {
    return axios.delete(`api/truck/${truckId}`)
  },
  searchtruck(licensePlatesFilter) {
    return axios.post(`api/truck/search`, licensePlatesFilter)
  },
  approveTruck(id) {
    return axios.get(`api/is-approve-truck/${id}`)
  }
}
