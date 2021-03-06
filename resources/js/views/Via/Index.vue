<template>
  <div>
    <title-bar :title-stack="['Admin', 'Via']"/>
    <hero-bar>
      Bán Via
      <router-link v-if="user.is_admin == 1" to="/via/new" class="button" slot="right">
        Rao bán
      </router-link>
    </hero-bar>
    <section class="section is-main-section">
      <card-component class="has-table has-mobile-sort-spaced" title="Bán Via" icon="account-multiple">
        <card-toolbar v-if="user.is_admin == 1">
          <button slot="right" type="button" class="button is-danger is-small has-checked-rows-number" @click="trashModal(null)" :disabled="!checkedRows.length">
            <b-icon icon="trash-can" custom-size="default"/>
            <span>Xóa tất cả</span>
            <span v-show="!!checkedRows.length">({{ checkedRows.length }})</span>
          </button>
        </card-toolbar>
        <modal-box
          :is-active="isModalActive"
          :trash-object-name="trashSubject"
          @confirm="trashConfirm"
          @cancel="trashCancel"
        />

        <b-table v-if="user.is_admin == 1"
          :checked-rows.sync="checkedRows"
          :checkable="true"
          :loading="isLoading"
          :paginated="paginated"
          :per-page="perPage"
          :striped="true"
          :hoverable="true"
          default-sort="name"
          :data="via">

            <b-table-column label="UID" field="uid" v-slot="props">
              <b style="color: #084B8A">{{ props.row.uid }}</b>
            </b-table-column>
            <b-table-column label="Năm" field="created_date" v-slot="props">
              <b style="color: #A4A4A4">{{ props.row.created_date }}</b>
            </b-table-column>
            <b-table-column label="Quốc gia" field="country" sortable v-slot="props">
              <b style="color: #A4A4A4">{{ props.row.country }}</b>
            </b-table-column>
            <b-table-column label="Giá" field="cost" v-slot="props">
              <b style="color: #DF013A">{{ formatPrice(props.row.cost) }}đ</b>
            </b-table-column>
            <b-table-column custom-key="actions" class="is-actions-cell" v-slot="props">
              <div class="buttons is-right">
                <router-link :to="{name:'via.edit', params: {id: props.row.id}}" class="button is-small is-primary">
                  <b-icon icon="account-edit" size="is-small"/>
                </router-link>
                <button class="button is-small is-danger" type="button" @click.prevent="trashModal(props.row)">
                  <b-icon icon="trash-can" size="is-small"/>
                </button>
              </div>
            </b-table-column>

          <section class="section" slot="empty">
            <div class="content has-text-grey has-text-centered">
              <template v-if="isLoading">
                <p>
                  <b-icon icon="dots-horizontal" size="is-large"/>
                </p>
                <p>Fetching data...</p>
              </template>
              <template v-else>
                <p>
                  <b-icon icon="emoticon-sad" size="is-large"/>
                </p>
                <p>Nothing's here&hellip;</p>
              </template>
            </div>
          </section>
        </b-table>
        <b-table v-else
          :loading="isLoading"
          :paginated="paginated"
          :per-page="perPage"
          :striped="true"
          :hoverable="true"
          default-sort="name"
          :data="via">

            <b-table-column label="UID" field="uid" v-slot="props">
              <b style="color: #084B8A">{{ props.row.uid }}</b>
            </b-table-column>
            <b-table-column label="Năm" field="created_date" v-slot="props">
              <b style="color: #A4A4A4">{{ props.row.created_date }}</b>
            </b-table-column>
            <b-table-column label="Quốc gia" field="country" sortable v-slot="props">
              <b style="color: #A4A4A4">{{ props.row.country }}</b>
            </b-table-column>
            <b-table-column label="Giá" field="cost" v-slot="props">
              <b style="color: #DF013A">{{ formatPrice(props.row.cost) }}đ</b>
            </b-table-column>
            <b-table-column custom-key="actions" class="is-actions-cell" v-slot="props">
              <div class="buttons">
                <router-link :to="{name:'via.buy', params: {id: props.row.id}}" class="button is-small is-primary">
                  <b-icon icon="cart-outline" size="is-small"/>
                </router-link>
              </div>
            </b-table-column>

          <section class="section" slot="empty">
            <div class="content has-text-grey has-text-centered">
              <template v-if="isLoading">
                <p>
                  <b-icon icon="dots-horizontal" size="is-large"/>
                </p>
                <p>Fetching data...</p>
              </template>
              <template v-else>
                <p>
                  <b-icon icon="emoticon-sad" size="is-large"/>
                </p>
                <p>Nothing's here&hellip;</p>
              </template>
            </div>
          </section>
        </b-table>
      </card-component>
    </section>
  </div>

</template>

<script>
import map from 'lodash/map'
import CardComponent from '@/components/CardComponent'
import ModalBox from '@/components/ModalBox'
import TitleBar from '@/components/TitleBar'
import HeroBar from '@/components/HeroBar'
import CardToolbar from '@/components/CardToolbar'

export default {
  name: "ViaIndex",
  components: {CardToolbar, HeroBar, TitleBar, ModalBox, CardComponent},
  data () {
    return {
      isModalActive: false,
      trashObject: null,
      via: [],
      user: [],
      isLoading: false,
      paginated: false,
      perPage: 20,
      checkedRows: []
    }
  },
  computed: {
    trashSubject () {
      if (this.trashObject) {
        return this.trashObject.name
      }

      if (this.checkedRows.length) {
        return `${this.checkedRows.length} entries`
      }

      return null
    }
  },
  created () {
    this.getData()
    this.getUser()
  },
  methods: {
    formatPrice(value) {
        let val = (value/1).toFixed(0).replace('.', ',')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    },
    getData () {
      this.isLoading = true
      axios
        .get('/api/via')
        .then(r => {
          this.isLoading = false
          if (r.data && r.data.data) {
            if (r.data.data.length > this.perPage) {
              this.paginated = true
            }
            this.via = r.data.data
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
    getUser () {
      this.isLoading = true
      axios
        .get('/api/user')
        .then(r => {
          this.isLoading = false
          if (r.data && r.data.data) {
            if (r.data.data.length > this.perPage) {
              this.paginated = true
            }
            this.user = r.data.data
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
    trashModal (trashObject = null) {
      if (trashObject || this.checkedRows.length) {
        this.trashObject = trashObject
        this.isModalActive = true
      }
    },
    trashConfirm () {
      let url
      let method
      let data = null

      this.isModalActive = false

      if (this.trashObject) {
        method = 'delete'
        url = `/api/via/${this.trashObject.id}/destroy`
      } else if (this.checkedRows.length) {
        method = 'post'
        url = '/api/via/destroy'
        data = {
          ids: map(this.checkedRows, row => row.id)
        }
      }

      axios({
        method,
        url,
        data
      }).then( r => {
        this.getData()

        this.trashObject = null
        this.checkedRows = []

        this.$buefy.snackbar.open({
          message: `Xóa thành công!`,
          queue: false
        })
      }).catch( err => {
        this.$buefy.toast.open({
          message: `Error: ${err.message}`,
          type: 'is-danger',
          queue: false
        })
      })
    },
    trashCancel () {
      this.isModalActive = false
    }
  }
}
</script>
