/** @format */

import axios from '@/axios'

export default {
  getTrucks(status) {
    console.log(status)
    return axios.get(`api/truck/list-truck/${status}`)
  },
  getTruck(truckId) {
    return axios.get(`api/truck/${truckId}`)
  },
  getCityName() {
    return axios.get(`api/truck/get-city-name`)
  },
  updateTruck(truck) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    const id = truck.get('truck_id')
    return axios.post(`api/truck/${id}`, truck, config)
  },
  createTruck(truck) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    return axios.post('api/truck', truck, config)
  },
  deleteTruck(truckId) {
    return axios.delete(`api/truck/${truckId}`)
  },
  searchTruck(licensePlatesFilter) {
    return axios.post(`api/truck/search`, licensePlatesFilter)
  },
  approveTruck(id) {
    return axios.get(`api/truck/is-approve-truck/${id}`)
  }
}
