/** @format */

import Vue from 'vue'
import Vuex from 'vuex'
import userManagement from './modules/user-management.js'
import auth from './modules/auth.js'
import app from './modules/app.js'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    // todo: moduleTodo
    user: userManagement,
    auth,
    app
  }
})
