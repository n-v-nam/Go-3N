/** @format */

import axios from '@/axios'

export default {
  getTrucksOfDriver() {
    return axios.get('api/driver')
  },
  getTruckOfDriver(id) {
    return axios.get(`api/driver/${id}`)
  },
  createTrucksByDriver(data) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    return axios.post('api/driver', data, config)
  },
  updateTruckByDriver(data) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    const id = data.get('truck_id')
    return axios.post(`api/driver/${id}`, data, config)
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
  },
  updatePostByDriver(data) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    const id = data.get('post_id')
    return axios.post(`api/driver-post/${id}`, data, config)
  },
  getPostsByDriver(data) {
    return axios.post(`api/driver-post/list-post/${data.isApprove}/${data.status}`)
  },
  getPostByDriver(id) {
    return axios.get(`api/driver-post/${id}`)
  },
  deletePostByDriver(id) {
    return axios.delete(`api/driver-post/${id}`)
  },
  acceptReceiveItems(orderId) {
    return axios.get(`api/driver-post/accept-customer-book-order/${orderId}`)
  },
  getSuggestTruck(suggestTruckId) {
    return axios.get(`api/driver-post-book/view-suggest/${suggestTruckId}`)
  },
  getListSuggestTruck() {
    return axios.get(`api/driver-post/list-suggest`)
  },
  getListOrder() {
    return axios.get(`api/driver-post/list-order`)
  },
  acceptSuggestTruck(suggestTruckId) {
    return axios.get(`api/driver-post/accept-suggest-truck/${suggestTruckId}`)
  },
  getListOrderByTruck(truckId) {
    return axios.get(`api/driver-post/list-order/${truckId}`)
  }
}
