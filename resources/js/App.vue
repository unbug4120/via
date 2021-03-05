<template>
  <div id="app">
    <nav-bar/>
    <aside-menu :menu="menu"/>
    <router-view/>
  </div>
</template>

<script>
// @ is an alias to /src
import NavBar from '@/components/NavBar'
import AsideMenu from '@/components/AsideMenu'
import FooterBar from '@/components/FooterBar'

export default {
  name: 'home',
  components: {
    FooterBar,
    AsideMenu,
    NavBar
  },
  computed: {
    menu () {
      return [
        [
          {
            to: '/',
            icon: 'home-outline',
            label: 'Trang chủ'
          }
        ],
        '-- Chợ rao bán --',
        [
          {
            to: '/via',
            label: 'Bán Via',
            icon: 'bullhorn',
          },
          {
            to: '/bm',
            label: 'Bán BM',
            icon: 'bullhorn',
          },
        ],
        '-- Tool --',
        [
          {
            to: '/clients/index',
            label: 'Check Hotmail',
            icon: 'checkbox-marked',
          },
          {
            to: '/clients/index',
            label: 'Check Facebook',
            icon: 'checkbox-marked',
          },
          {
            to: '/clients/index',
            label: 'Tạo mã 2FA',
            icon: 'shield',
          }
        ],
      ]
    }
  },
  created () {
    axios
      .get('api/user')
      .then(r => {
        this.$store.commit('user', r.data.data)
      })
      .catch(err => {
        this.$buefy.toast.open({
          message: `Error: ${err.message}`,
          type: 'is-danger'
        })
      })
  }
}
</script>
