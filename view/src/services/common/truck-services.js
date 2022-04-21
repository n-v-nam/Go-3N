/** @format */

import axios from '@/axios'

export default {
  async getTrucks(data) {
    return axios.get(`api/truck/list-truck/${data.status}`)
  },
  async getTruck(truckId) {
    return axios.get(`api/truck/${truckId}`)
  },
  async getCityName() {
    return axios.get(`api/truck/get-city-name`)
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
