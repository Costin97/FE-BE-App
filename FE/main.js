import "./assets/style.css";
import { createApp } from "vue";
import App from "./App.vue";
import axios from "axios";
import router from "./router";
import { createStore } from "vuex";
import PrimeVue from "primevue/config";

import Column from "primevue/column";
import DataTable from "primevue/datatable";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";

axios.defaults.headers.common["Access-Control-Allow-Origin"] = "*";

const store = createStore({
  state() {
    return {
      token: "",
      time: "",
    };
  },
  mutations: {
    setToken(state, val) {
      state.token = val;
    },
    setTime(state, val) {
      state.time = val;
    },
  },
});

const app = createApp(App);

app.use(router);
app.use(store);
app.use(PrimeVue);

app.component("Column", Column);
app.component("DataTable", DataTable);
app.component("Button", Button);
app.component("Dialog", Dialog);
app.component("InputText", InputText);
app.component("InputNumber", InputNumber);

app.mount("#app");
