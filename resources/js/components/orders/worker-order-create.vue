<template>
  <v-row class="justify-content-around">
    <v-col cols="12" lg="6" md="8" sm="10" xl="5">
      <v-data-table
          :headers="headers"
          :items="menuItems"
          :items-per-page="-1"
          class="elevation-1"
      >
        <template slot="item" slot-scope="props">
          <tr>
            <td class="text-xs-left">{{ props.item.name }}</td>
            <td class="text-xs-left">{{ props.item.price}}</td>
            <td class="text-xs-center">
              <v-btn @click="addToOrder(props.item)">Dodaj do zamówienia</v-btn>
            </td>
          </tr>
        </template>
      </v-data-table>
    </v-col>
    <v-col cols="12" lg="6" md="8" sm="10" xl="5">
      <v-card class="transparent_form">
        <v-card-title>
          Tworzenie zamówienia
        </v-card-title>
        <v-card-text>
          <v-data-table
              :headers="orderedItemsHeaders"
              :items="orderedItems"
              :items-per-page="-1"
              class="elevation-1"
          >
            <template slot="item" slot-scope="props">
              <tr>
                <td class="text-xs-left">{{ props.item.name }}</td>
                <td class="text-xs-left">{{ props.item.price}}</td>
                <td class="text-xs-left">
                  <!--									//TODO zmienić na strzałeczki-->
                  <v-text-field
                      @change="changeTotalSum"
                      v-model="props.item.amount">
                  </v-text-field>
                </td>
                <td class="text-xs-center">
                  <v-icon @click="deleteItem(props.item.id)">
                    delete
                  </v-icon>
                </td>
              </tr>
            </template>
          </v-data-table>
          <h5 style="margin-top: 2rem;">Suma zamówienia (zł):</h5>
          <v-text-field
              readonly
              style="max-width: 5rem"
              v-model="orderSum">
          </v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-row class="justify-center">
            <v-btn v-bind:loading="loading" @click="order" class="yellow_form_button" color="secondary">
              Zamów
            </v-btn>
          </v-row>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
  import {notificationError, notificationSuccess} from "../../Notifications";

  export default {
    name: "create-order-waiter",
    props: ['tableid'],
    data() {
      return {
        loading: false,
        menuItems: [],
        headers: [
          {text: 'Nazwa', value: 'name',},
          {text: 'Cena', value: 'price'},
          {text: 'Akcje', value: ''},
        ],
        orderedItemsHeaders: [
          {text: 'Nazwa', value: 'name',},
          {text: 'Cena', value: 'price'},
          {text: 'Ilość', value: ''},
          {text: 'Usuń', value: ''},
        ],
        orderedItems: [],
        orderSum: ''
      }
    },
    beforeMount() {
      this.getData()
    },
    methods: {
      getData() {
        axios.get(route('api.dish.index'))
          .then(response => {
            this.menuItems = response.data;
            this.menuItems.forEach(item => {
              item.amount = 0
            })
          }).catch(error => {
          console.error(error)
        })
      },
      addToOrder(dish) {
        dish.amount = 1;
        this.orderedItems.push(dish);
        for (let i = 0; i < this.menuItems.length; i++) {
          if (this.menuItems[i].id === dish.id) {
            this.menuItems.splice(i, 1);
          }
        }
        this.changeTotalSum()
      },
      deleteItem(id) {
        for (let i = 0; i < this.orderedItems.length; i++) {
          if (this.orderedItems[i].id === id) {
            this.orderedItems[i].amount = 0;
            this.menuItems.push(this.orderedItems[i]);
            this.orderedItems.splice(i, 1);
          }
        }
        this.changeTotalSum()
      },
      changeTotalSum() {
        this.orderSum = 0;
        for (let i = 0; i < this.orderedItems.length; i++) {
          this.orderSum += this.orderedItems[i].amount * this.orderedItems[i].price
        }
      },
      order() {
        this.loading = true;
        let orderArray = [];
        this.orderedItems.forEach(item => {
          orderArray.push({amount: item.amount, dishId: item.id});
        });
        let _this = this;
        axios.post(route('api.order.storeNewOrderFromWorker'), {
          table_id: this.tableid,
          items: orderArray,
        }).then(
          response => {
            notificationSuccess(response.data);
            window.location.href = route('table.showWaiter', _this.tableid);
          },
          error => {
            if (error.response.status === 422) {
              notificationError("Podano niepoprawne dane, spróbuj jeszcze raz");
            } else {
              notificationError(error.response.data);
            }
          },
        ).finally(() => {
          this.loading = false;
        })
      }
    }
  }
</script>

<style scoped>

</style>