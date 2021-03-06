<template>
  <div>
    <title-bar :title-stack="titleStack" />
    <hero-bar>
      {{ heroTitle }}
      <router-link slot="right" to="/user/via" class="button">
        Quay lại
      </router-link>
    </hero-bar>
    <section class="section is-main-section">
      <tiles>
        <card-component
          v-if="isProfileExists"
          title="Thông tin Via"
          icon="account"
          class="tile is-child"
        >
          <form @submit.prevent="submit">
            <b-field label="- UID -">
              <b style="color: #084b8a">{{ item.uid }}</b>
            </b-field>
            <b-field label="- Mật khẩu -">
              <b style="color: #084b8a">{{ item.password }}</b>
            </b-field>
            <b-field label="- Avatar -">
              <img :src="item.avatar" />
            </b-field>
            <b-field label="- Quốc gia -">
              <b style="color: #a4a4a4">{{ item.country }}</b>
            </b-field>
            <hr />
            <b-field label="- Năm tạo Via -">
              <b style="color: #a4a4a4">{{ item.created_date }}</b>
            </b-field>
          </form>
        </card-component>
      </tiles>
    </section>
  </div>
</template>

<script>
import clone from "lodash/clone";
import TitleBar from "@/components/TitleBar";
import HeroBar from "@/components/HeroBar";
import Tiles from "@/components/Tiles";
import CardComponent from "@/components/CardComponent";
import FilePicker from "@/components/FilePicker";
import Notification from "@/components/Notification";

export default {
  name: "ClientForm",
  components: {
    FilePicker,
    CardComponent,
    Tiles,
    HeroBar,
    TitleBar,
    Notification,
  },
  props: {
    id: {
      default: null,
    },
  },
  data() {
    return {
      isLoading: false,
      item: null,
      form: this.getClearFormObject(),
      createdReadable: null,
    };
  },
  computed: {
    titleStack() {
      let lastCrumb;

      if (this.isProfileExists) {
        lastCrumb = this.form.name;
      } else {
        lastCrumb = "Thông tin";
      }

      return ["Admin", "Via", lastCrumb];
    },
    heroTitle() {
      if (this.isProfileExists) {
        return this.form.name;
      } else {
        return "Thông tin Via";
      }
    },
    isProfileExists() {
      return !!this.item;
    },
  },
  created() {
    this.getData();
  },
  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(0).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    getClearFormObject() {
      return {
        id: null,
        uid: null,
        password: null,
        cost: null,
        created_date: null,
        country: null,
      };
    },
    getData() {
      if (this.id) {
        axios
          .get(`/api/viapanel/${this.id}`)
          .then((r) => {
            this.form = r.data.data;
            this.item = clone(r.data.data);
          })
          .catch((e) => {
            this.item = null;

            this.$buefy.toast.open({
              message: `Error: ${e.message}`,
              type: "is-danger",
              queue: false,
            });
          });
      }
    },
    fileIdUpdated(fileId) {
      this.form.file_id = fileId;
      this.form.avatar_filename = null;
    },
    input(v) {
      //this.createdReadable = moment(v).format('MMM D, Y').toString()
    }
  },
  watch: {
    id(newValue) {
      this.form = this.getClearFormObject();
      this.item = null;

      if (newValue) {
        this.getData();
      }
    },
  },
};
</script>
