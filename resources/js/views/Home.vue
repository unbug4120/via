<template>
  <div>
    <title-bar :title-stack="titleStack"/>
    <hero-bar :has-right-visible="false">
      Trang chủ
    </hero-bar>
    <section class="section is-main-section">
      <tiles>
        <card-widget class="tile is-child" type="is-primary" icon="account-multiple" :number="homes.user" label="Người dùng"/>
        <card-widget class="tile is-child" type="is-info" icon="cart-outline" :number="homes.via" label="Đang bán"/>
        <card-widget class="tile is-child" type="is-success" icon="cart-outline" :number="homes.via_history" label="Đã bán"/>
      </tiles>
    </section>
  </div>
</template>

<script>
// @ is an alias to /src
import TitleBar from '@/components/TitleBar'
import HeroBar from '@/components/HeroBar'
import Tiles from '@/components/Tiles'
import CardWidget from '@/components/CardWidget'
import CardComponent from '@/components/CardComponent'
import ClientsTableSample from '@/components/ClientsTableSample'
export default {
  name: 'home',
  components: {
    ClientsTableSample,
    CardComponent,
    CardWidget,
    Tiles,
    HeroBar,
    TitleBar
  },
  data () {
    return {
      homes: [],
      isLoading: false,
    }
  },
  computed: {
    titleStack () {
      return [
        'Admin',
        'Dashboard'
      ]
    }
  },
  mounted () {
    this.$buefy.snackbar.open({
      message: 'Welcome back',
      queue: false
    })
  },
  created () {
    this.getData()
  },
  methods: {
    getData () {
      this.isLoading = true
      axios
        .get('api/home')
        .then(r => {
          this.isLoading = false
          if (r.data && r.data.data) {
            if (r.data.data.length > this.perPage) {
              this.paginated = true
            }
            this.homes = r.data.data
          }
        })
        .catch( err => {
          this.isLoading = false
          this.$buefy.toast.open({
            message: `Error: ${err.message}`,
            type: 'is-danger',
            queue: false
          })
        })
    },
  }
}
</script>
