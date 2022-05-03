/** @format */

import postService from '@/services/common/post'

const state = () => ({
  post: [],
  city: {}
})

const getters = {
  getPost: state => state.posts,
  getCity: state => Object.values(state.city),
  getDistrict: state => state.district
}

const mutations = {
  SET_POST(state, payload) {
    state.post = payload
  },
  SET_CITY(state, payload) {
    state.city = payload
  }
}

const actions = {
  searchPost(store, data) {
    return postService.searchPost(data)
  },
  viewPost(store, postId) {
    return postService.viewPost(postId)
  },
  bookTruck(store, data) {
    return postService.bookTruck(data)
  },
  createPost(store, data) {
    return postService.createPost(data)
  },
  getPosts(store, data) {
    return postService.getPosts(data)
  },
  getPost(store, id) {
    return postService.getPost(id)
  },
  approvePost(store, id) {
    return postService.approvePost(id)
  },
  deletePost(store, id) {
    return postService.deletePost(id)
  },
  updatePost(store, data) {
    return postService.updatePost(data)
  },
  async getCityName({ commit }) {
    const res = await postService.getCityName()
    commit('SET_CITY', res.data)
  },
  async getDistrict(commit, cityId) {
    return await postService.getDistrict(cityId)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
