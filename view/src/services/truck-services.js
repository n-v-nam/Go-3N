/** @format */

import axios from '@/axios'

export default {
  async getTrucks() {
    return axios.get('api/truck')
  },
  async getTruck(truckId) {
    return axios.get(`api/truck/${truckId}`)
  },
  async updateTruck(truck) {
    return axios.put(`api/truck/${truck.truck_id}`, truck)
  },
  async createTruck(truck) {
    return axios.post('api/truck', truck)
  },
  async deleteTruck(truckId) {
    return axios.delete(`api/truck/${truckId}`)
  },
  async searchtruck(licensePlatesFilter) {
    return axios.post(`api/truck/search`, licensePlatesFilter)
  }
}
