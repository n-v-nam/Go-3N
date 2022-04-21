/** @format */

import postService from '@/services/client/post-services'

const state = () => ({
  post: []
})

const getters = {
  getPost: (state) => state.posts
}

const mutations = {
  SET_POST(state, payload) {
    state.post = payload
  }
}

const actions = {
  async searchPost(commit, data) {
    const res = await postService.searchPost(data)
    return res.data
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
