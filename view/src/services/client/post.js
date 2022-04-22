/** @format */

import axios from '@/axios'

export default {
  searchPost(data) {
    return axios.post('api/customer-book-truck/search-post', data)
  },
  bookTruck(id) {
    return axios.post(`api/customer-book-truck/book-truck/${id}`)
  },
  getPosts(status) {
    return axios.get(`api/post/list-post/is-approve/${status}`)
  },
  updatePost(data) {
    return axios.put(`api/update-post/${data.post_id}`, data)
  },
  approvePost(id) {
    return axios.get(`api/is-approve-post/${id}`)
  },
  createPost(data) {
    return axios.post('api/post', data)
  },
  deletePost(id) {
    return axios.delete(`api/post/${id}`)
  },
  getPost(id) {
    return axios.get(`api/post${id}`)
  },
  getCityName() {
    return axios.get(`api/get-city`)
  },
  getDistrict(cityId) {
    return axios.get(`api/get-district/${cityId}`)
  }
}
