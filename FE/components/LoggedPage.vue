<template #body="rowdata">
  <div class="logoutContainer">
    <div class="card flex justify-content-center">
      <Button icon="pi pi-power-off" label: Lo @click="logoutTrigger()"
      severity="secondary" />
      <Dialog v-model:visible="visible_4" modal :style="{ width: '50vw' }">
        <p style="font-size: 18px; font-family: Roboto; font-weight: bold">
          Are you sure you want to logout?
        </p>
        <template #footer>
          <Button label="Cancel" text @click="cancelMethodLogout" />
          <Button label="LogOut" @click="logoutMethod()" autofocus />
        </template>
      </Dialog>
    </div>
  </div>
  <div class="tableContainer">
    <DataTable
      :value="products"
      paginator
      :rows="3"
      :rowsPerPageOptions="[3, 6, 20, 50]"
      tableStyle="min-width: 50rem"
    >
      <Column field="id" header="Id" style="display: none"></Column>
      <Column field="name" header="Name" style="width: 25%"></Column>
      <Column field="price" header="Price" style="width: 25%"></Column>
      <Column
        field="description"
        header="Description"
        style="width: 25%"
      ></Column>

      <Column field="edit" header="Edit">
        <template #body="rowdata">
          <div class="card flex justify-content-center">
            <Button
              icon="pi pi pi-pencil"
              @click="editTrigger(rowdata)"
              severity="secondary"
            />
            <Dialog
              v-model:visible="visible_1"
              modal
              header="Edit Form"
              :style="{ width: '50vw' }"
            >
              <div class="inputContainer">
                <div class="p-inputgroup flex-1">
                  <span class="p-inputgroup-addon">
                    <i class="pi pi-info-circle"></i>
                  </span>
                  <InputText v-model="this.name_e" placeholder="Name" />
                </div>

                <div class="p-inputgroup flex-1">
                  <span class="p-inputgroup-addon">$</span>
                  <InputText v-model="this.price_e" placeholder="Price" />
                </div>

                <div class="p-inputgroup flex-1">
                  <span class="p-inputgroup-addon">
                    <i class="pi pi-exclamation-circle"></i>
                  </span>
                  <InputText
                    v-model="this.description_e"
                    placeholder="Description"
                  />
                </div>
              </div>
              <template #footer>
                <Button
                  label="Cancel"
                  icon="pi pi-times"
                  text
                  @click="cancelMethodEdit"
                />
                <Button
                  label="Edit"
                  icon="pi pi-pencil"
                  @click="editMethod(rowdata)"
                  autofocus
                />
              </template>
            </Dialog>
          </div> </template
      ></Column>
      <Column field="delete" header="Delete">
        <template #body="rowdata">
          <div class="card flex justify-content-center">
            <Button
              icon="pi pi pi-times"
              @click="deleteTrigger()"
              severity="secondary"
            />
            <Dialog
              v-model:visible="visible_2"
              modal
              header="Delete?"
              :style="{ width: '50vw' }"
            >
              <p>Are you sure you want to delete this row?</p>
              <template #footer>
                <Button label="Cancel" text @click="cancelMethodDelete" />
                <Button
                  label="Delete"
                  @click="deleteMethod(rowdata)"
                  autofocus
                />
              </template>
            </Dialog>
          </div> </template
      ></Column>
    </DataTable>
    <div class="card flex justify-content-center addContainer">
      <Button label="Add Data" @click="addTrigger()" severity="secondary" />
      <Dialog
        v-model:visible="visible_3"
        modal
        header="Data Form"
        :style="{ width: '50vw' }"
      >
        <div class="inputContainer">
          <div class="p-inputgroup flex-1">
            <span class="p-inputgroup-addon">
              <i class="pi pi-info-circle"></i>
            </span>
            <InputText v-model="name" placeholder="Name" />
          </div>

          <div class="p-inputgroup flex-1">
            <span class="p-inputgroup-addon">$</span>
            <InputText v-model="price" placeholder="Price" />
          </div>

          <div class="p-inputgroup flex-1">
            <span class="p-inputgroup-addon">
              <i class="pi pi-exclamation-circle"></i>
            </span>
            <InputText v-model="description" placeholder="Description" />
          </div>
          <div v-if="this.invalid" class="invalidContainer">
            <p>Please fill out all the fields!</p>
          </div>
        </div>
        <template #footer>
          <Button label="Cancel" text @click="cancelMethodAdd" />
          <Button label="Add" @click="addMethod()" autofocus />
        </template>
      </Dialog>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import "primevue/resources/themes/lara-light-indigo/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
export default {
  data() {
    return {
      products: null,
      token: this.$store.state.token,
      expTime: this.$store.state.time,
      visible_1: false,
      visible_2: false,
      visible_3: false,
      visible_4: false,
      name: "",
      price: "",
      description: "",
      name_e: "",
      price_e: "",
      description_e: "",
      id: null,
      invalid: false,
      interval: null,
    };
  },
  methods: {
    setup() {
      this.$router.push("/");
    },
    getData() {
      axios
        .get("http://localhost/loginPage/public/user/getdata")
        .then((result) => {
          this.products = [...result.data];
        })
        .catch(function (e) {
          console.log(e);
        });
    },
    editTrigger(rowdata) {
      this.visible_1 = true;
      this.name_e = rowdata.data.name;
      this.price_e = rowdata.data.price;
      this.description_e = rowdata.data.description;
    },
    editMethod(rowdata) {
      this.visible = false;
      const data = {
        id: rowdata.data.id,
        name: this.name_e,
        price: this.price_e,
        description: this.description_e,
      };
      axios
        .post("http://localhost/loginPage/public/user/editdata", data)
        .then(() => {
          this.products = null;
          this.visible_1 = false;
          this.cancelMethodEdit();
          this.getData();
        })
        .catch(function (e) {
          console.log(e);
        });
    },
    deleteTrigger() {
      this.visible_2 = true;
    },
    logoutTrigger() {
      this.visible_4 = true;
    },
    logoutMethod() {
      for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        const item = localStorage.getItem(key);
        if (item === this.token) {
          localStorage.removeItem("token");
          localStorage.removeItem("expirationTime");
          break;
        }
      }
      this.setup();
    },
    deleteMethod(rowdata) {
      axios
        .delete("http://localhost/loginPage/public/user/deletedata", {
          data: { id: rowdata.data.id },
        })
        .then(() => {
          this.products = null;
          this.visible_2 = false;
          this.getData();
        })
        .catch(function (e) {
          console.log(e);
        });
    },
    addTrigger() {
      this.visible_3 = true;
    },
    addMethod() {
      const data = {
        name: this.name,
        price: this.price,
        description: this.description,
      };
      if (data.name && data.price && data.description) {
        this.invalid = false;
        axios
          .post("http://localhost/loginPage/public/user/adddata", data)
          .then(() => {
            this.products = null;
            this.visible_3 = false;
            this.name = "";
            this.price = "";
            this.description = "";
            this.cancelMethodAdd();
            this.getData();
          })
          .catch(function (e) {
            console.log(e);
          });
      } else {
        this.invalid = true;
      }
    },
    cancelMethodEdit() {
      this.name = "";
      this.price = "";
      this.description = "";
      this.visible_1 = false;
    },

    cancelMethodDelete() {
      this.visible_2 = false;
    },
    cancelMethodAdd() {
      this.visible_3 = false;
      this.invalid = false;
    },
    cancelMethodLogout() {
      this.visible_4 = false;
    },
  },
  created() {
    this.getData();
  },
  mounted() {
    this.interval = setInterval(() => {
      const expirationTime = localStorage.getItem("expirationTime");
      if (
        expirationTime !== null &&
        Math.floor(Date.now() / 1000) >= parseInt(expirationTime)
      ) {
        localStorage.removeItem("token");
        localStorage.removeItem("expirationTime");
        this.setup();
      }
    }, 60000);
  },

  beforeUnmount() {
    clearInterval(this.interval);
  },
};
</script>
