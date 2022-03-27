/** @format */

import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    // =============================================================================
    // FULL PAGE LAYOUTS
    // =============================================================================
    // {
    //   path: '',
    //   // component: () => import('@/layouts/full-page/FullPage.vue'),
    //   children: [
    //     // =============================================================================
    //     // PAGES
    //     // =============================================================================
    //     {
    //       path: '/pages/error-404',
    //       name: 'page-error-404',
    //       // component: () => import('@/views/pages/Error404.vue'),
    //       meta: {
    //         rule: 'editor'
    //       }
    //     }
    //   ]
    // },
    // Redirect to 404 page, if no match found
    {
      path: '',
      redirect: '/admin-dashboard'
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/pages/Login.vue'),
      meta: {
        rule: 'editor'
      }
    },
    {
      path: '/admin',
      name: 'admin',
      redirect: '/admin-dashboard',
      component: () => import('@/layouts/AdminMain.vue'),
      children: [
        {
          path: '/admin-dashboard',
          name: 'admin-dashboard',
          component: () => import('@/pages/admin/Dashboard.vue'),
          meta: {
            rule: 'admin'
          }
        },
        {
          path: '/admin-user',
          name: 'admin-user',
          component: () => import('@/pages/admin/UserManage.vue'),
          meta: {
            rule: 'admin'
          }
        }
      ]
    }
  ]
})

export default router
