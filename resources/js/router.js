import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'

Vue.use(Router)

export default new Router({
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/tables',
      name: 'tables',
      component: () => import('./views/Tables.vue')
    },
    {
      path: '/forms',
      name: 'forms',
      component: () => import('./views/Forms.vue')
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('./views/Profile.vue')
    },
    {
      path: '/via',
      name: 'via.index',
      component: () => import('./views/Via/Index.vue'),
    },
    {
      path: '/via/new',
      name: 'via.new',
      component: () => import('./views/Via/Form.vue'),
    },
    {
      path: '/via/:id',
      name: 'via.edit',
      component: () => import('./views/Via/Form.vue'),
      props: true
    },
    {
      path: '/via/:id/buy',
      name: 'via.buy',
      component: () => import('./views/Via/Buy.vue'),
      props: true
    },
    {
      path: '/user/rank',
      name: 'user.rank',
      component: () => import('./views/User/Rank.vue'),
    },
    {
      path: '/user/via',
      name: 'viapanel.index',
      component: () => import('./views/ViaPanel/Index.vue'),
    },
    {
      path: '/user/via/:id',
      name: 'via.info',
      component: () => import('./views/ViaPanel/Info.vue'),
      props: true
    },
    {
      path: '/tool/hotmail/check',
      name: 'hotmail.check',
      component: () => import('./views/Tool/HotMail/Check.vue'),
      props: true
    },
    {
      path: '/tool/facebook/check',
      name: 'facebook.check',
      component: () => import('./views/Tool/Facebook/Check.vue'),
      props: true
    },
  ],
  scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  }
})
