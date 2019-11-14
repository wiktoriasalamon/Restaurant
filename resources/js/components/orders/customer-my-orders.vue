<template>
  <v-card>
    <v-data-table
      :headers="headers"
      :items="myOrders"
      :items-per-page="-1"
      class="elevation-1"
      single-expand
      :expanded.sync="expanded"
      show-expand
    >
      <template v-slot:expanded-item="{ headers }">
        <v-data-table
        :headers="watchedHeaders"
          :items="watchedOrder"
          class="elevation-1"
          hide-default-footer>
          
        </v-data-table>
        <tr>
          <td>Cena łącznie: {{watchedSum}}</td>
        </tr>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import { log } from "util";
export default {
  name: "customer-my-orders",
  data() {
    return {
      myOrders: [],
      expanded: [],
      watchedOrder: [],
      watchedSum: 0,
      headers: [
        { text: "Nr zamówienia", value: "id" },
        { text: "Data i godzina", value: "date" },
        { text: "Adres dostawy", value: "address" },
        { text: "", value: "data-table-expand" }
      ],
      watchedHeaders: [
        { text: "Nazwa", value: "name" },
        { text: "Cena", value: "price" },
        { text: "Ilość", value: "amount" },
      ]
    };
  },
  watch: {
    expanded: function(list) {
      if (this.expanded.length>0) {
        this.watchedOrder = [],
          axios
            .get(
              route("api.order.loadOrder", {
                token: this.expanded[0].token
              })
            )
            .then(response => {
              const entries = Object.entries(response.data);
              if (response.data) {
                this.watchedSum = response.data.sum;
                response.data.dishes.forEach(item => {
                  var newItem = {
                    name: item.name,
                    price: item.price,
                    amount: item.amount
                  };
                  this.watchedOrder.push(newItem);
                });
              }
            })
            .catch(error => {
              console.error(error);
            });
      }
    }
  },
  beforeMount() {
    axios
      .get(route("api.order.customerOrder"))
      .then(response => {
        const entries = Object.entries(response.data);
        if (response.data) {
          for (let [key, value] of entries) {
            var address = JSON.parse(value.address);

            var add =
              address.street +
              " " +
              address.houseNumber +
              "/" +
              address.apartmentNumber +
              " " +
              address.city;
            this.myOrders.push({
              id: value.id,
              address: add,
              date: value.created_at,
              token: value.token
            });
          }
        }
      })
      .catch(error => {
        console.error(error);
      });
  },
  methods: {
    seeMore() {}
  }
};
</script>

<style scoped>
</style>