<template>
  <v-row class="justify-content-around">
    <v-col cols="12" sm="10" md="8" lg="6" xl="5" v-if="this.statusOrder === 'ordered'">
      <v-card class="transparent_form">
        <v-card-title>
          <h1>Dania</h1>
        </v-card-title>
        <v-text-field
            v-model="search"
            append-icon="search"
            label="Search"
            single-line
            hide-details
        ></v-text-field>
        <v-data-table
            :headers="headers"
            :items="menuItems"
            :items-per-page="5"
            class="elevation-1"
            :search="search"
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
      </v-card>

    </v-col>
    <v-col cols="12" sm="10" md="8" lg="6" xl="5">
      <v-card class="transparent_form">
        <v-card-title>
          <h2>Edycja zamówienia</h2>
        </v-card-title>
        <v-card-text>
          <h5>Status zamówienia: </h5>

          <v-row class="justify-space-between" style="margin-bottom: 2rem; ">
            <v-col>
              <v-select
                  :items="statusItems"
                  item-value="status"
                  item-text="status_pl"
                  label="Status"
                  v-model="orderStatus"
              ></v-select>
            </v-col>
            <v-col>
              <v-btn @click="changeStatus" v-bind:loading="statusLoading" class="yellow_form_button" color="secondary">
                Zmień status
              </v-btn>
            </v-col>
          </v-row>
          <v-data-table
              :headers="orderedItemsHeaders"
              :items="orderedItems"
              :items-per-page="-1"
              class="elevation-1"
          >
            <template v-slot:item.changeAmount="{ item }">
              <v-icon @click="minusItem(item)" :disabled="orderChangeDisabled">indeterminate_check_box</v-icon>
              <v-icon @click="plusItem(item)" :disabled="orderChangeDisabled">add_box</v-icon>
            </template>
            <template v-slot:item.delete="{ item }">
              <v-icon @click="deleteItem(item)" :disabled="orderChangeDisabled">delete</v-icon>
            </template>
          </v-data-table>
          <h5 style="margin-top: 2rem;">Suma zamówienia:</h5>
          <v-text-field
              readonly
              v-model="orderSum"
              style="max-width: 5rem">

          </v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-row class="justify-center">
            <v-btn @click="updateOrder" :disabled="orderChangeDisabled" v-bind:loading="orderLoading"
                   class="yellow_form_button" color="secondary">
              Aktualizuj zamówienie
            </v-btn>
          </v-row>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
  import {notification} from '../../Notifications.js';

  export default {
    name: "worker-order-edit",
    props: ['token', 'status'],
    data() {
      return {
				search: '',
				menuItems: [],
        headers: [
          {text: 'Nazwa', value: 'name',},
          {text: 'Cena (zł)', value: 'price'},
          {text: 'Akcje', value: ''},
        ],
        orderedItemsHeaders: [
          {text: 'Nazwa', value: 'name',},
          {text: 'Cena (zł)', value: 'price'},
          {text: 'Ilość', value: 'amount'},
          {text: "Zmień ilość", value: "changeAmount"},
          {text: 'Usuń', value: 'delete'},
        ],
        orderedItems: [],
        statusItems: [],
        orderStatus: '',
        orderSum: '',
        statusLoading: false,
        orderLoading: false,
        orderChangeDisabled: false,
        statusOrder: ''
      }
    },
    mounted() {
      this.statusOrder = this.status
    },
    beforeMount() {

      this.getMenuData();
      this.getOrder();
      this.getStatusItems()
    },
    watch: {
      orderedItems: function () {
        this.changeTotalSum();
      }
    },
    methods: {
      getMenuData() {
        axios.get(route('api.dish.index'))
          .then(response => {
            this.menuItems = response.data;
            this.menuItems.forEach(item => {
              item.amount = 0
            });
            console.log(this.menuItems)
          }).catch(error => {
          console.error(error)
        })
      },
      getOrder() {
        axios.get(route('api.order.loadOrder', this.token))
          .then(response => {
            this.orderedItems = response.data.dishes;
            this.orderSum = response.data.sum;
            this.orderStatus = response.data.status;
            console.log(this.orderStatus)
            this.orderChangeDisabled = this.orderStatus !== "ordered";
          }).catch(error => {
          console.error(error)
        })
      },
      getStatusItems() {
        axios.get(route('api.order.fetchOrderStatusTypes'))
          .then(response => {
            this.statusItems = response.data
          }).catch(error => {
          console.error(error)
        })
      },
      addToOrder(item) {
        let add = true;
        for (let i = 0; i < this.orderedItems.length; i++) {
          if (this.orderedItems[i].id === item.id) {
            this.orderedItems[i].amount++;
            this.changeTotalSum()
            add = false;
            break
          }
        }
        if (add === true) {
          var newItem = {
            id: item.id,
            name: item.name,
            price: item.price,
            amount: 1
          };
          this.orderedItems.push(newItem);
          this.changeTotalSum()
        }
        for (let i = 0; i < this.menuItems.length; i++) {
          if (this.menuItems[i].id === item.id) {
            this.menuItems.splice(i, 1);
          }
        }
      },
      changeTotalSum() {
        this.orderSum = 0
        for (let i = 0; i < this.orderedItems.length; i++) {
          this.orderSum += this.orderedItems[i].amount * this.orderedItems[i].price
        }
        this.orderSum = this.orderSum.toFixed(2);
      },
      deleteItem(item) {
        for (let i = 0; i < this.orderedItems.length; i++) {
          if (this.orderedItems[i].id === item.id) {
            this.orderedItems[i].amount = 0;
            this.menuItems.push(this.orderedItems[i]);
            this.orderedItems.splice(i, 1);
          }
        }
        this.changeTotalSum()
      },
      updateOrder() {
        this.orderLoading = true
        let orderArray = [];
        this.orderedItems.forEach(item => {
          orderArray.push({amount: item.amount, dishId: item.id});
        });
        console.log(this.token);
        axios.post(route('api.order.updateOrderFromWorker'), {
          token: this.token,
          items: orderArray,
        }).then(
          response => {
            notification(response.data, 'success');
            setTimeout(function () {
              window.location.href = route('order.index')
            }, 5000);
          },
          error => {
            if (error.response.status === 422) {
              notification("Podano niepoprawne dane, spróbuj jeszcze raz", 'error');
            } else {
              notification(error.response.data, 'error');
            }
          },
        ).finally(() => {
          this.orderLoading = false;
        });
      },
      changeStatus() {
        this.statusLoading = true
        axios.post(route('api.order.changeStatusOrder'), {
          token: this.token,
          status: this.orderStatus,
        }).then(
          response => {
            notification(response.data, 'success');
          },
          error => {
            if (error.response.status === 422) {
              notification("Podano niepoprawne dane, spróbuj jeszcze raz", 'error');
            } else {
              notification(error.response.data, 'error');
            }
          },
        ).finally(() => {
          this.statusLoading = false;
          this.statusOrder = this.orderStatus
          this.orderChangeDisabled = this.orderStatus !== "ordered";
        });

      },
      minusItem(item) {
        if (item.amount > 1) {
          item.amount = item.amount - 1;
          this.changeTotalSum();
        }
      },
      plusItem(item) {
        if (item.amount < 15) {
          item.amount = item.amount + 1;
          this.changeTotalSum();
        }
      },
    }
  }
</script>

<style scoped>

</style>