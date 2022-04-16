/** @format */

import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store/store'

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
      path: '/home',
      name: 'Trang chủ ',
      component: () => import('@/pages/user/Home.vue'),
      meta: {
        rule: 'user',
        title: 'Trang chủ'
      }
    },
    {
      path: '',
      redirect: '/home',
      component: () => import('@/layouts/user/Main.vue'),
      children: [
        {
          path: '/login',
          name: 'Đăng nhập',
          component: () => import('@/pages/user/page/Login.vue'),
          meta: {
            rule: 'user',
            img: '@/assets/img/user/bg-login.png',
            title: 'Đăng nhập'
          }
        },
        {
          path: '/register',
          name: 'Đăng ký',
          component: () => import('@/pages/user/page/Register.vue'),
          meta: {
            rule: 'user',
            img: '@/assets/img/user/bg-login.png',
            title: 'Đăng ký'
          }
        },
        {
          path: '/page/profile',
          name: 'Thông tin người dùng',
          component: () => import('@/pages/user/page/Profile.vue'),
          meta: {
            rule: 'user',
            img: '@/assets/img/user/bg-login.png',
            title: 'Thông tin người dùng'
          }
        },
        {
          path: '/booking',
          name: 'Đặt xe',
          component: () => import('@/pages/user/page/Booking.vue'),
          meta: {
            rule: 'user',
            img: '@/assets/img/user/bg-login.png',
            title: 'Đặt xe'
          }
        }
      ]
    },
    {
      path: '/admin-login',
      name: 'admin-login',
      component: () => import('@/pages/admin/Login.vue'),
      meta: {
        rule: 'user'
      }
    },
    {
      path: '/reset-password',
      name: 'reset-password',
      component: () => import('@/pages/admin/ResetPassword.vue'),
      meta: {
        rule: 'user'
      }
    },
    {
      path: '/admin',
      name: 'admin',
      redirect: '/admin-dashboard',
      component: () => import('@/layouts/admin/AdminMain.vue'),
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
        },
        {
          path: '/admin-profile',
          name: 'admin-profile',
          component: () => import('@/pages/admin/Profile.vue'),
          meta: {
            rule: 'admin'
          }
        },
        {
          path: '/admin-customer',
          name: 'admin-customer',
          component: () => import('@/pages/admin/CustomerManage.vue'),
          meta: {
            rule: 'admin'
          }
        },
        {
          path: '/admin-truck',
          name: 'admin-truck',
          component: () => import('@/pages/admin/TruckManage.vue'),
          meta: {
            rule: 'admin'
          }
        }
      ]
    },
    {
      path: '*',
      name: 'Có lỗi xảy ra',
      component: () => import('@/pages/user/ErrorPage.vue'),
      meta: {
        rule: 'user',
        img: '@/assets/img/user/bg-login.png',
        title: 'Lỗi'
      }
    }
  ]
})

router.beforeEach(async (to, from, next) => {
  if (!to.meta || !to.meta.rule || to.meta.rule == 'user') next()
  if (to.meta && to.meta.rule !== 'user' && !store.state.auth.profile.type) {
    await store.dispatch('auth/getProfile')
  }
  if (to.meta.rule == 'admin' && store.state.auth.profile.type !== 1) {
    store.dispatch('app/setErrorNotification', 'Bạn không có quyền truy cập trang này !')
    if (from.path.search('admin') != -1) {
      store.dispatch('auth/clearToken')
      return {
        path: '/admin-login'
      }
    }
    store.dispatch('authClient/setToken')
    return {
      path: '/login'
    }
  } else if (to.meta.rule == 'editor' && store.state.auth.profile.type === 3) {
    store.dispatch('app/setErrorNotification', 'Bạn không có quyền truy cập trang này !')
    if (from.path.search('admin') != -1) {
      return {
        path: '/admin'
      }
    }
    return {
      path: '/home'
    }
  } else {
    next()
  }
})

export default router
