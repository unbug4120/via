<template>
  <div>
    <title-bar :title-stack="titleStack" />
    <hero-bar>
      {{ heroTitle }}
      <router-link slot="right" to="/via" class="button">
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
            <b-field label="- Avatar -">
              <img :src="item.avatar" />
            </b-field>
            <b-field label="- Giá bán -">
              <b style="color: #df013a">{{ formatPrice(item.cost) }}đ</b>
            </b-field>
            <b-field label="- Quốc gia -">
              <b style="color: #a4a4a4">{{ item.country }}</b>
            </b-field>
            <hr />
            <b-field label="- Năm tạo Via -">
              <b style="color: #a4a4a4">{{ item.created_date }}</b>
            </b-field>
            <hr />
            <b-field>
              <b-button
                type="is-primary"
                :loading="isLoading"
                native-type="submit"
                >Mua ngay</b-button
              >
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
        lastCrumb = "Rao bán";
      }

      return ["Admin", "Via", lastCrumb];
    },
    heroTitle() {
      if (this.isProfileExists) {
        return this.form.name;
      } else {
        return "Rao bán Via";
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
          .get(`/api/via/${this.id}`)
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
    },
    submit() {
      this.isLoading = true;
      axios
        .post(`api/via/${this.id}/buy`, {
          data: this.form,
        })
        .then((r) => {
          if (r.data.data.message == 1) {
            this.$buefy.snackbar.open({
              message: "Tài khoản không đủ!",
              queue: false,
              type: "is-danger",
            });
            setTimeout(() => this.$router.go(this.$router.currentRoute), 2000);
          } else {
            this.isLoading = false;
            this.item = r.data.data;
            this.$buefy.snackbar.open({
              message: "Mua thành công!",
              queue: false,
            });
            this.$router.push({ name: "via.index" });
          }
        })
        .catch((e) => {
          this.isLoading = false;

          this.$buefy.toast.open({
            message: `Error: ${e.message}`,
            type: "is-danger",
            queue: false,
          });
        });
    },
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
