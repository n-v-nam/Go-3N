/** @format */

import Vue from 'vue'
import Vuex from 'vuex'
import user from './modules/admin/user.js'
import customer from './modules/admin/customer.js'
import truck from './modules/common/truck.js'
import auth from './modules/admin/auth.js'
import clientAuth from './modules/client/auth.js'
import app from './modules/common/app.js'
import item from './modules/common/item.js'
import categoryTruck from './modules/common/category-truck'
import post from './modules/common/post'
import driver from './modules/client/driver'
import reservation from './modules/client/reservation'
import notification from './modules/common/notification'
import payment from './modules/client/payment'
import order from './modules/admin/order'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    // todo: moduleTodo
    user,
    customer,
    truck,
    auth,
    clientAuth,
    app,
    item,
    categoryTruck,
    post,
    notification,
    driver,
    reservation,
    payment,
    order
  }
})
