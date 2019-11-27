<template>
  <v-row class="justify-center align-center">
    <v-col cols="12" lg="6" ma-2 md="8" sm="12" xl="5">
      <v-card class="transparent_form">
        <v-card-title>
          <h1>Moje zamówienia</h1>
      </v-card-title>
      
      
      <v-data-table
          :expanded.sync="expanded"
          :headers="headers"
          :items="myOrders"
          :items-per-page="-1"
          class="elevation-1"
          show-expand
          single-expand
      >
        <template v-slot:expanded-item="{ headers }">
          <td :colspan="headers.length">
            <v-data-table
                class="elevation-0"
                :headers="watchedHeaders"
                :items="watchedOrder"
                style="background-color: transparent!important;"
                hide-default-footer
            ></v-data-table>
            <v-spacer/>
            <tr>
              <v-card-text>
                Cena łącznie: {{watchedSum}} zł
              </v-card-text>
            </tr>
          </td>
        </template>
      </v-data-table>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
  export default {
    name: "customer-my-orders",
    data() {
      return {
        myOrders: [],
        expanded: [],
        watchedOrder: [],
        watchedSum: 0,
        headers: [
          {text: "Nr zamówienia", value: "id"},
          {text: "Data i godzina", value: "date"},
          {text: "Adres dostawy", value: "address"},
          {text: "Status", value: "status_pl"},
          {text: "", value: "data-table-expand"}
        ],
        watchedHeaders: [
          {text: "Nazwa", value: "name"},
          {text: "Cena", value: "price"},
          {text: "Ilość", value: "amount"}
        ]
      };
    },
    watch: {
      expanded: function (list) {
        if (this.expanded.length > 0) {
          (this.watchedOrder = []),
            axios
              .get(
                route("api.order.loadOrder", {
                  token: this.expanded[0].token
                })
              )
              .then(response => {
                const entries = Object.entries(response.data);
                if (response.data) {
                  this.watchedSum = response.data.sum.toFixed(2);
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
              var add = address.street + " " + address.houseNumber;
              if (address.apartmentNumber) {
                add += "/" + address.apartmentNumber;
              }
              add += " " + address.city;
              this.myOrders.push({
                id: value.id,
                address: add,
                date: value.created_at,
                status_pl: value.status_pl,
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
      seeMore() {
      }
    }
  };
</script>

<style scoped>
</style>