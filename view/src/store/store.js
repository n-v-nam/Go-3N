/** @format */

import Vue from 'vue'
import Vuex from 'vuex'
import userManagement from './modules/admin/user-management.js'
import customerManagement from './modules/admin/customer-management.js'
import truckManagement from './modules/common/truck-management.js'
import auth from './modules/admin/auth.js'
import authClient from './modules/client/auth.js'
import app from './modules/common/app.js'
import itemManagement from './modules/common/item-management.js'
import categoryTruckManagement from './modules/common/category-truck-management'
import post from './modules/client/post'
import driver from './modules/client/driver'
import customerNotification from './modules/client/customer-notification'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    // todo: moduleTodo
    user: userManagement,
    customer: customerManagement,
    truck: truckManagement,
    auth,
    authClient,
    app,
    itemManagement,
    categoryTruckManagement,
    post,
    customerNotification,
    driver
  }
})
