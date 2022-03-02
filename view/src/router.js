/** @format */

import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const router = new Router({
  mode: 'history',
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
      path: '/login',
      name: 'login',
      component: () => import('./pages/Login.vue'),
      meta: {
        rule: 'editor'
      }
    }
  ]
})

export default router