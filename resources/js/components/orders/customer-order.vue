<template>
  <v-stepper class="background" v-model="e1">
    <v-stepper-header>
      <v-stepper-step :complete="e1 > 1" step="1">Wybierz dania</v-stepper-step>
      <v-divider></v-divider>
      <v-stepper-step :complete="e1 > 2" step="2">Uzupełnij swoje dane</v-stepper-step>
      <v-divider></v-divider>
      <v-stepper-step step="3">Potwierdź zamówienie</v-stepper-step>
    </v-stepper-header>
    <v-stepper-items>
      <v-stepper-content class="background" step="1">
        <v-card class="background">
          <v-row class="justify-space-between">
            <v-col>
              <v-simple-table>
                <template v-slot:default>
                  <thead>
                  <tr>
                    <th class="text-left">Kategorie</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td @click="setMenuItems(-1)">Wszystkie</td>
                  </tr>
                  <tr :key="item.id" v-for="item in categoryItems">
                    <td @click="setMenuItems(item.id)">{{ item.name }}</td>
                  </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-col>
            <v-col>
              <v-card-title>
                <v-text-field
                    append-icon="search"
                    hide-details
                    label="Szukaj"
                    single-line
                    v-model="search"
                ></v-text-field>
              </v-card-title>
              <v-data-table
                  :headers="headers"
                  :items="menuItems"
                  :items-per-page="5"
                  :search="search"
                  class="elevation-1"
                  show-select
                  v-model="selected"
              ></v-data-table>
            </v-col>
            <v-col>
              <v-data-table
                  :headers="orderedHeaders"
                  :items="ordered"
                  :items-per-page="5"
                  class="elevation-1"
              >
                <template v-slot:item.changeAmount="{ item }">
                  <v-icon @click="minusItem(item)">indeterminate_check_box</v-icon>
                  <v-icon @click="plusItem(item)">add_box</v-icon>
                </template>
                <template v-slot:item.delete="{ item }">
                  <v-icon @click="deleteItem(item)">delete</v-icon>
                </template>
              </v-data-table>
            </v-col>
          </v-row>
        </v-card>
        <v-btn @click="goToStep2" color="secondary">Dalej</v-btn>
      </v-stepper-content>
      <v-stepper-content class="background" step="2">
        <v-card>
          <v-card-title>Dane do zamówienia</v-card-title>
          <v-card-text>
            <v-form
              ref="form"
            >
              <v-text-field
                  :rules="[rules.required, rules.emailRules]"
                  label="E-mail"
                  v-model="form.email"
                  :disabled="mailDisabled"
              ></v-text-field>
              <v-text-field :rules="[rules.required]" label="Ulica" v-model="form.address.street"></v-text-field>
              <v-text-field :rules="[rules.required]"  label="Numer domu "
                            v-model="form.address.houseNumber"></v-text-field>
              <v-text-field label="Numer mieszkania"
                            v-model="form.address.apartmentNumber"></v-text-field>
              <v-text-field
                  :rules="[rules.required]"
                  label="Miejscowość"
                  v-model="form.address.city"
              ></v-text-field>
              <v-text-field
                  :rules="[rules.required, rules.postCodeFormat]"
                  label="Kod pocztowy"
                  v-model="form.address.postCode"
              ></v-text-field>
            </v-form>
          </v-card-text>
        </v-card>
        <v-btn @click="saveAddress" color="secondary">Dalej</v-btn>
        <v-btn @click="e1 = 1" text>Wróć</v-btn>
      </v-stepper-content>
      <v-stepper-content class="background" step="3">
        <v-card class="background">
          <v-row class="justify-space-between">
            <v-col>
              <v-card class="mx-auto" max-width="375">
                <v-list two-line>
                  <v-list-item>
                    <v-list-item-icon>
                      <v-icon color="indigo">monetization_on</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title>{{this.priceSum + ' zł'}}</v-list-item-title>
                      <v-list-item-subtitle>Kwota całkowita</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                  <v-list-item>
                    <v-list-item-icon>
                      <v-icon color="indigo">mail</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title>{{this.form.email}}</v-list-item-title>
                      <v-list-item-subtitle>e-mail</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                  <v-list-item>
                    <v-list-item-icon>
                      <v-icon color="indigo">room</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title v-if="this.form.address.apartmentNumber">
                        {{this.form.address.street + " " + this.form.address.houseNumber+ "/" +
                        this.form.address.apartmentNumber}}
                      </v-list-item-title>
                      <v-list-item-content v-else>
                        {{ this.form.address.street + " " + this.form.address.houseNumber }}
                      </v-list-item-content>
                      <v-list-item-subtitle>Ulica i numer</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                  <v-list-item>
                    <v-list-item-icon></v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title>
                        {{this.form.address.postCode + " " + this.form.address.city}}
                      </v-list-item-title>
                      <v-list-item-subtitle>Miejscowość</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
              </v-card>
            </v-col>
            <v-col>
              <v-data-table
                  :headers="summaryOrderHeaders"
                  :items="ordered"
                  :items-per-page="5"
                  class="elevation-1"
              ></v-data-table>
            </v-col>
          </v-row>
        </v-card>

        <v-btn @click="completeOrderOnline" color="secondary" v-bind:loading="loading">Zamów</v-btn>
        <v-btn @click="e1 = 2" text>Wróć</v-btn>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>
</template>

<script>

  import {notification} from "../../Notifications";

  export default {
    name: "customer-order",
    props: ["dishes", "categories"],
    data() {
      return {
        loading: false,
        e1: 0,
        search: "",
        selected: [],
        ordered: [],
        menuItems: [],
        orderArray: [],
        priceSum: 0,
        headers: [
          {text: "Nazwa", value: "name"},
          {text: "Cena (zł)", value: "price"}
        ],
        orderedHeaders: [
          {text: "Nazwa", value: "name"},
          {text: "Cena (zł)", value: "price"},
          {text: "Ilość", value: "amount"},
          {text: "Zmień ilość", value: "changeAmount"},
          {text: "Usuń", value: "delete"}
        ],
        summaryOrderHeaders: [
          {text: "Nazwa", value: "name"},
          {text: "Cena (zł)", value: "price"},
          {text: "Ilość", value: "amount"}
        ],
        categoryItems: [],
        allMenuItems: [],
        mailDisabled: false,
        form: {
          id: "",
          name: "",
          surname: "",
          email: "",
          address: {
            street: "",
            houseNumber: "",
            apartmentNumber: "",
            postCode: "",
            city: ""
          },
          phone: ""
        },
        rules: {
          required: value => !!value || "To pole jest wymagane",
          emailRules: v =>
            /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
            "Niepoprawny adres email",
          phoneMax12: value =>
            value.length <= 12 ||
            "Numer telefonu powinien mieć mniej niż 13 znaków",
          postCodeFormat: value =>
            /^\d{2}-\d{3}$/.test(value) || "Nieprawidłowy format kodu pocztowego",
          min6: v => v.length >= 6 || "Hasło musi mieć conajmniej 6 znaków",
          passwordRules: value =>
            (value && !value.localeCompare(this.passwordForm.newPassword)) ||
            "Hasła nie są takie same"
        }
      };
    },
    beforeMount() {
      this.menuItems = this.dishes;
      this.allMenuItems = this.dishes;
      this.categoryItems = this.categories;
      this.getData();
    },

    watch: {
      selected: function (list) {
        this.ordered = [];
        list.forEach(item => {
          var newItem = {
            id: item.id,
            name: item.name,
            price: item.price,
            amount: 1
          };
          this.ordered.push(newItem);
          this.calculateSum();
        });
      },
    },
    methods: {
      getData() {
        axios.get(route('api.user.authenticatedUser'))
          .then(response => {
            const entries = Object.entries(response.data);
            if (response.data !== "unauthenticated") {
              this.mailDisabled = true;
              for (let [key, value] of entries) {
                if (key === 'address') {
                  let address = Object.entries(JSON.parse(value));
                  for (let [addressKey, addressValue] of address) {
                    this.form.address[addressKey] = addressValue
                  }
                } else {
                  this.form[key] = value;
                }

              }
            }
          })
      },
      calculateSum() {
        this.priceSum = 0;
        this.ordered.forEach(item => {
          this.priceSum =
            this.priceSum + parseFloat(item.amount) * parseFloat(item.price);
        });
        this.priceSum = this.priceSum.toFixed(2);
      },
      setMenuItems(id) {
        if (id === -1) {
          this.menuItems = this.allMenuItems;
        } else {
          this.menuItems = [];
          this.allMenuItems.forEach(item => {
            if (item.category_id === id) {
              this.menuItems.push(item);
            }
          });
        }
        return this.menuItems;
      },
      deleteItem(deletedItem) {
        this.selected.forEach(item => {
          if (item.id === deletedItem.id) {
            this.selected = this.arrayRemove(this.selected, item);
          }
        });
      },
      minusItem(item) {
        if (item.amount > 1) {
          item.amount = item.amount - 1;
          this.calculateSum();
        }
      },
      plusItem(item) {
        if (item.amount < 15) {
          item.amount = item.amount + 1;
          this.calculateSum();
        }
      },
      arrayRemove(arr, value) {
        return arr.filter(function (ele) {
          return ele != value;
        });
      },
      completeOrderOnline() {
        this.loading = true;
        this.ordered.forEach(item => {
          this.addItemToNewOrder(item.id, item.amount);
        });
        axios
          .post(route("api.order.storeNewOrderOnline"), {
            takeaway: false,
            address: this.form.address,
            items: this.orderArray,
            email: this.form.email,
          })
          .then(
            response => {
              notification(response.data.message, 'error');
              this.orderArray = [];
              window.location.href = route('order.create.online');

            }).catch(error => {
          if (error.response.status === 422) {
            notification("Podano niepoprawne dane, spróbuj jeszcze raz", 'error');
          } else if (error.response.status === 500) {
            notification("Nie udało się złożyć zamówienia. Wystąpił błąd na serwerze.", 'error');
          } else {
            notification(error.response.data, 'error');
          }
        }).finally(() => {
          this.loading = false;
        })
      },
      addItemToNewOrder(dishId, amount) {
        this.orderArray.push({dishId: dishId, amount: amount});
      },
      saveAddress() {
        if (this.$refs.form.validate()) {
          let formAddress = this.form.address;
          formAddress = JSON.stringify(formAddress);
          this.e1 = 3;
        }
      },
      goToStep2() {
        if (this.ordered.length > 0) {
          this.e1 = 2;
        } else {
          notification("Nie wybrano żadnego dania.", 'error');
        }
      }
    }
  };
</script>

<style scoped>
  .background {
    background-color: rgba(255, 255, 255, 0.65) !important;
    box-shadow: none;
  }

  .background.v-card.v-sheet.theme--light {
    background: none !important;
  }
</style>