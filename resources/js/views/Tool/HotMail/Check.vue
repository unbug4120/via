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
          :title="formCardTitle"
          icon="account-edit"
          class="tile is-child"
        >
          <form @submit.prevent="submit">
            <template v-if="id">
              <b-field label="ID" horizontal>
                <b-input :value="id" custom-class="is-static" readonly />
              </b-field>
              <hr />
            </template>
            <hr />
            <b-field label="Hotmail" horizontal>
              <b-input type="textarea" placeholder="Nhập mỗi dòng 1 email.." v-model="form.mail" required/>
            </b-field>
            <hr />
            <b-field horizontal>
              <b-button
                type="is-primary"
                :loading="isLoading"
                native-type="submit"
                >Thực thi</b-button
              >
            </b-field>
          </form>
        </card-component>
      </tiles>
    </section>
  </div>
</template>

<script>
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
        lastCrumb = "Check Hotmail";
      }

      return ["Admin", lastCrumb];
    },
    heroTitle() {
      if (this.isProfileExists) {
        return this.form.name;
      } else {
        return "Check Hotmail";
      }
    },
    formCardTitle() {
      if (this.isProfileExists) {
        return "Sửa Via";
      } else {
        return "Check Hotmail";
      }
    },
    isProfileExists() {
      return !!this.item;
    },
  },
  created() {
  },
  methods: {
    getClearFormObject() {
      return {
        mail: null,
      };
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
      let method = "post";
      let url = "api/tool/hotmail/check";

      axios({
        method,
        url,
        data: this.form,
      })
        .then((r) => {
          this.isLoading = false;
          this.item = r.data.data;
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
