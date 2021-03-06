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
            <b-field label="UID" horizontal>
              <b-input
                placeholder="100042489660547"
                v-model="form.uid"
                required
              />
            </b-field>
            <b-field label="Mật khẩu" horizontal>
              <b-input placeholder="*****" v-model="form.password" required />
            </b-field>
            <b-field label="Giá bán" horizontal>
              <b-input placeholder="100000" v-model="form.cost" required />
            </b-field>
            <b-field label="Quốc gia" horizontal>
              <b-select
                placeholder="Chọn quốc gia"
                v-model="form.country"
                required
              >
                <option
                  v-for="(country, index) in countrys"
                  :key="index"
                  :value="country"
                >
                  {{ country }}
                </option>
              </b-select>
            </b-field>
            <b-field label="Năm tạo Via" horizontal>
              <b-input placeholder="2010" v-model="form.created_date" required />
            </b-field>
            <hr />
            <b-field horizontal>
              <b-button
                type="is-primary"
                :loading="isLoading"
                native-type="submit"
                >Rao bán</b-button
              >
            </b-field>
          </form>
        </card-component>
        <card-component
          v-if="isProfileExists"
          title="Thông tin Via"
          icon="account"
          class="tile is-child"
        >
          <b-field label="UID">
            <b-input :value="item.uid" custom-class="is-static" readonly />
          </b-field>
          <b-field label="Mật khẩu">
            <b-input :value="item.password" custom-class="is-static" readonly />
          </b-field>
          <b-field label="Giá bán">
            <b-input :value="item.cost" custom-class="is-static" readonly />
          </b-field>
          <b-field label="Quốc gia">
            <b-input :value="item.country" custom-class="is-static" readonly />
          </b-field>
          <hr />
          <b-field label="Năm tạo Via">
            <b-input :value="item.created_date" custom-class="is-static" readonly />
          </b-field>
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
      countrys: [
        "Ả Rập Xê Út",
        "Afghanistan",
        "Ai Cập",
        "Albania",
        "Algeria",
        "Ấn Độ",
        "Andorra",
        "Angola",
        "Anh",
        "Antigua và Barbuda",
        "Áo",
        "Argentina",
        "Armenia",
        "Azerbaijan",
        "Ba Lan",
        "Bắc Macedonia",
        "Bahamas",
        "Bahrain",
        "Bangladesh",
        "Barbados ",
        "Belarus",
        "Belize",
        "Bénin",
        "Bhutan",
        "Bỉ",
        "Bờ Biển Ngà",
        "Bồ Đào Nha",
        "Bolivia",
        "Bosnia và Herzegovina",
        "Botswana",
        "Brazil",
        "Brunei",
        "Bulgaria",
        "Burkina Faso",
        "Burundi",
        "Các tiểu vương quốc Ả Rập Thống nhất",
        "Cameroon",
        "Campuchia",
        "Canada",
        "Cape Verde",
        "Chad",
        "Chile",
        "Colombia",
        "Comoros",
        "Cộng hòa Congo",
        "Cộng hòa dân chủ Congo",
        "Cộng hòa Dominican",
        "Cộng hòa Séc",
        "Cộng hòa Trung Phi",
        "Costa Rica",
        "Croatia",
        "Cuba",
        "Djibouti",
        "Dominica",
        "Đài Loan",
        "Đan Mạch",
        "Đông Timor",
        "Đức",
        "Ecuador",
        "El Salvador",
        "Eritrea ",
        "Estonia",
        "Ethiopia",
        "Fiji",
        "Gabon",
        "Gambia",
        "Georgia",
        "Ghana",
        "Grenada",
        "Guatemala",
        "Guinea",
        "Guinea Xích đạo",
        "Guinea-Bissau",
        "Guyana",
        "Hà Lan",
        "Haiti",
        "Hàn Quốc",
        "Hoa Kỳ",
        "Honduras",
        "Hungary",
        "Hy Lạp",
        "Iceland",
        "Indonesia",
        "Iran",
        "Iraq",
        "Ireland",
        "Israel",
        "Jamaica",
        "Jordan",
        "Kazakhstan",
        "Kenya",
        "Kiribati",
        "Kosovo",
        "Kuwait",
        "Kyrgyzstan",
        "Lào",
        "Latvia",
        "Lesotho",
        "Liban",
        "Liberia",
        "Libya",
        "Liechtenstein",
        "Liên bang Micronesia",
        "Lithuania",
        "Luxembourg",
        "Ma-rốc",
        "Madagascar",
        "Malawi",
        "Malaysia",
        "Maldives",
        "Mali",
        "Malta",
        "Mauritania",
        "Mauritius",
        "Mexico ",
        "Moldova",
        "Monaco",
        "Mông Cổ",
        "Montenegro",
        "Mozambique",
        "Myanmar",
        "Na Uy",
        "Nam Phi",
        "Nam Sudan",
        "Namibia",
        "Nauru",
        "Nepal",
        "New Zealand",
        "Nga",
        "Nhật Bản",
        "Nicaragua",
        "Niger",
        "Nigeria",
        "Oman",
        "Pakistan",
        "Palau",
        "Palestine",
        "Panama",
        "Papua",
        "New Guinea",
        "Paraguay",
        "Peru",
        "Phần Lan",
        "Pháp",
        "Philippines",
        "Qatar",
        "Quần đảo Marshall",
        "Quần đảo Solomon",
        "Romania",
        "Rwanda",
        "Saint Kitts và Nevis Saint Lucia",
        "Saint Vincent và Grenadines Samoa",
        "San Marino São Tomé và Príncipe Senegal",
        "Serbia",
        "Seychelles",
        "Sierra Leone",
        "Singapore",
        "Síp",
        "Slovakia",
        "Slovenia",
        "Somalia",
        "Sri Lanka",
        "Sudan",
        "Suriname",
        "Swaziland",
        "Syria",
        "Tajikistan",
        "Tanzania",
        "Tây Ban Nha",
        "Thái Lan",
        "Thành Vatican",
        "Thổ Nhĩ Kỳ",
        "Thụy Điển",
        "Thụy Sĩ",
        "Togo",
        "Tonga",
        "Triều Tiên",
        "Trinidad và Tobago",
        "Trung Quốc",
        "Tunisia",
        "Turkmenistan",
        "Tuvalu",
        "Úc",
        "Uganda",
        "Ukraine",
        "Uruguay",
        "Uzbekistan",
        "Vanuatu",
        "Venezuela",
        "Việt Nam",
        "Ý",
        "Yemen",
        "Zambia",
        "Zimbabwe",
      ],
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
    formCardTitle() {
      if (this.isProfileExists) {
        return "Sửa Via";
      } else {
        return "Thêm Via";
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
      let method = "post";
      let url = "api/via/store";

      if (this.id) {
        method = "patch";
        url = `api/via/${this.id}`;
      }

      axios({
        method,
        url,
        data: this.form,
      })
        .then((r) => {
          this.isLoading = false;

          if (!this.id && r.data.data.id) {
            this.$router.push({
              name: "clients.edit",
              params: { id: r.data.data.id },
            });

            this.$buefy.snackbar.open({
              message: "Đăng thành công!",
              queue: false,
            });
            this.$router.push({ name: "via.index" });
          } else {
            this.item = r.data.data;

            this.$buefy.snackbar.open({
              message: "Sửa thành công!",
              queue: false,
            });
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
