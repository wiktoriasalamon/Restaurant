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
                    <tr v-for="item in categoryItems" :key="item.id">
                      <td @click="setMenuItems(item.id)">{{ item.name }}</td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-col>
            <v-col>
              <v-card-title>
                <v-text-field
                  v-model="search"
                  append-icon="search"
                  label="Szukaj"
                  single-line
                  hide-details
                ></v-text-field>
              </v-card-title>
              <v-data-table
                v-model="selected"
                :headers="headers"
                :items="menuItems"
                :items-per-page="-1"
                :search="search"
                show-select
                class="elevation-1"
              ></v-data-table>
            </v-col>
            <v-col>
              <v-data-table
                :headers="orderedHeaders"
                :items="ordered"
                :items-per-page="-1"
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
        <v-btn color="primary" @click="goToStep2">Dalej</v-btn>
      </v-stepper-content>
      <v-stepper-content class="background" step="2">
        <v-card>
          <v-card-title>Dane do zamówienia</v-card-title>
          <v-card-text>
            <v-form>
              <v-text-field
                :rules="[rules.required, rules.emailRules]"
                label="E-mail"
                v-model="form.email"
              ></v-text-field>
              <v-text-field :rules="[rules.required]" label="Ulica" v-model="form.address.street"></v-text-field>
              <v-text-field label="Numer domu " v-model="form.address.houseNumber"></v-text-field>
              <v-text-field label="Numer mieszkania" v-model="form.address.apartmentNumber"></v-text-field>
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
        <v-btn color="primary" @click="saveAddress">Dalej</v-btn>
        <v-btn text @click="e1 = 1">Wróć</v-btn>
      </v-stepper-content>
      <v-stepper-content class="background" step="3">
        <v-card class="background">
          <v-row class="justify-space-between">
            <v-col>
              <v-card max-width="375" class="mx-auto">
                <v-list two-line>
                  <v-list-item>
                    <v-list-item-icon>
                      <v-icon color="indigo">monetization_on</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title>{{this.priceSum}}</v-list-item-title>
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
                      <v-list-item-title>
						  {{this.form.address.street + " " + this.form.address.houseNumber+ "/" + this.form.address.apartmentNumber}}
						  </v-list-item-title>
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
                :items-per-page="-1"
                class="elevation-1"
              ></v-data-table>
            </v-col>
          </v-row>
        </v-card>

        <v-btn color="primary" @click="completeOrderOnline">Zamów</v-btn>
        <v-btn text @click="e1 = 2">Wróć</v-btn>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>
</template>

<script>
import { log } from "util";
export default {
  name: "customer-order",
  props: ["dishes", "categories"],
  data() {
    return {
      e1: 0,
      search: "",
      selected: [],
      ordered: [],
      menuItems: [],
      orderArray: [],
      priceSum: 0,
      headers: [
        { text: "Nazwa", value: "name" },
        { text: "Cena", value: "price" }
      ],
      orderedHeaders: [
        { text: "Nazwa", value: "name" },
        { text: "Cena", value: "price" },
        { text: "Ilość", value: "amount" },
        { text: "Zmień ilość", value: "changeAmount" },
        { text: "Usuń", value: "delete" }
      ],
      summaryOrderHeaders: [
        { text: "Nazwa", value: "name" },
        { text: "Cena", value: "price" },
        { text: "Ilość", value: "amount" }
      ],
      categoryItems: [],
      allMenuItems: [],
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
  },

  watch: {
    selected: function(list) {
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
      return arr.filter(function(ele) {
        return ele != value;
      });
    },
    completeOrderOnline() {
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
			Vue.toasted
			.success(response.data.message)
			.goAway(5000);
			this.orderArray = [];
            window.location.href=route('order.create.online');
            
          },
          error => {
            if (error.response.status === 422) {
              Vue.toasted
                .error("Podano niepoprawne dane, spróbuj jeszcze raz")
                .goAway(3000);
            } else if (error.response.status === 500) {
				Vue.toasted
                .error("Nie udało się złożyć zamówienia. Wystąpił błąd na serwerze.")
                .goAway(3000);
			} else {
              Vue.toasted.error(error.response.data).goAway(3000);
            }
          }
        );
    },
    addItemToNewOrder(dishId, amount) {
      this.orderArray.push({ dishId: dishId, amount: amount });
    },
    saveAddress() {
      let formAddress = this.form.address;
      formAddress = JSON.stringify(formAddress);
      this.e1 = 3;
	},
	goToStep2() {
		if(this.ordered.length>0) {
			this.e1 = 2;
		} else {
			Vue.toasted.error("Nie wybrano żadnego dania.").goAway(3000);
		}
	}
  }
};
</script>

<style scoped>
.background {
  background: rgba(255, 255, 255, 0.5) !important;
}
</style>