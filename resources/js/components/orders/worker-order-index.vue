<template>
    <v-row class="justify-center align-center" >
        <v-col cols="12" sm="10" md="8" xl="5" lg="6">
              <v-card class="transparent_form">
        <v-card-title>
          <h1>Zamówienia</h1>
        </v-card-title>
      
            <v-select
              class="beige_select"
                    :items="orderSatuses"
                    item-value="status"
                    item-text="status_pl"
                    label="Status"
                    v-model="orderStatusPicked"
                    v-on:change="pickStatus()"
            ></v-select>
            <v-data-table
                    :headers="headers"
                    :items="orders"
                    :items-per-page="-1"
                    class="elevation-1"
                    loading-text="Ładowanie danych..."
                    v-bind:loading="loadingTable"
            >
                <template slot="item" slot-scope="props">
                    <tr>
                        <td class="text-xs-left">{{ props.item.table_id }}</td>
                        <td class="text-xs-left">{{onlyTime(props.item.created_at)}}</td>
                        <td class="text-xs-left">{{ props.item.status_pl}}</td>
                        <td class="text-xs-left">{{ props.item.worker_id}}</td>
                        <td class="text-xs-left">{{ props.item.takeaway? "TAK":"NIE"}}</td>
                        <td class="text-xs-left">
                            <v-icon @click="editItem(props.item.token)" small>
                                edit
                            </v-icon>
                            <v-icon @click="showItem(props.item.token)" small>
                                visibility
                            </v-icon>
                            <v-icon @click="deleteItem(props.item.token)" small>
                                delete
                            </v-icon>
                        </td>
                    </tr>
                </template>
            </v-data-table>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
  import {notification} from '../../Notifications.js';
  import Moment from 'moment';

  export default {
    name: "worker-order-index",
    data() {
      return {
        menuItems: [],
        headers: [
          {text: 'Numer stolika', value: 'table_id',},
          {text: 'Zamówiono', value: 'created-at',},
          {text: 'Status', value: 'status_pl'},
          {text: 'Kelner', value: 'worker_id'},
          {text: 'Zamówienie na wynos', value: 'takeaway'},
          {text: 'Akcje', value: ''},
        ],
        orderSatuses: [],
        orderStatusPicked: "myOrders",
        orders: [],
        clicked: '',
        lastToken: null,
        loadingTable: false

      }
    },
    created: function () {
      Echo.channel('order')
        .listen('OrderChanged', (e) => {
          this.getOrderStatuses()

          if (this.orderStatusPicked === "myOrders") {
            this.getMyOrders()
          } else {
            this.getOrders(this.orderStatusPicked)
          }
        });
    },
    beforeMount() {
      this.getOrderStatuses()
      this.getMyOrders()
    },
    methods: {
      onlyTime(date) {
        return Moment(date).format('HH:mm');
      },
      pickStatus() {
        if (this.orderStatusPicked === "myOrders") {
          this.getMyOrders()
        } else {
          this.getOrders(this.orderStatusPicked)
        }
      },
      getOrderStatuses() {
        axios.get(route('api.order.fetchOrderStatusTypes')).then(response => {
          let tmp = [];
          tmp.push({
            status: 'myOrders',
            status_pl: 'Moje zamówienia',
          })
          this.orderSatuses =tmp.concat(response.data )
        }).catch(error => {
          console.error(error)
        });
      },
      getOrders(statusName) {
        this.loadingTable = true;
        this.lastFetch = statusName;
        this.clicked = statusName;
        axios.get(route('api.order.orderWithStatus', statusName)).then(response => {
          this.orders = response.data;
          console.log(response.data)
        }).catch(error => {
          console.error(error)
        }).finally(() => {
          this.loadingTable = false;
        });
      },
      getMyOrders() {
        this.lastFetch = null;
        this.clicked = "myOrders"
        axios.get(route('api.order.myOrder')).then(response => {
          this.orders = response.data
        }).catch(error => {
          console.error(error)
        });
      },
      showItem(token) {
        window.location.href = route('order.show', [token])
      },
      editItem(token) {
        window.location.href = route('order.editWaiter', [token])
      },
      deleteItem(orderToken) {
        axios.delete(route('api.order.delete', {
          token: orderToken
        }))
          .then(response => {
            notification(response.data, 'success');
            if (this.lastFetch !== null) {
              this.getOrders(this.lastFetch);
            } else {
              this.getMyOrders();
            }
          }).catch(error => {
          notification("Wystąpił błąd podczas usuwania zamowienia", 'error');
          console.error(error.response);
        });
      },
    }
  }
</script>

<style scoped>

</style>