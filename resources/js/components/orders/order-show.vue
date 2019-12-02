<template>
    <v-row class="justify-center">
        <v-col cols="12" sm="12" md="8" lg="6" xl="4">
        <v-card class="transparent_form">
            <v-card-title>
                <h2>Podgląd zamówienia</h2>
            </v-card-title>
            <v-card-text>
                <h5 style="margin-bottom: 2rem;">Status zamówienia: {{orderStatus}}</h5>
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
                            <td class="text-xs-left">{{props.item.amount}}</td>
                        </tr>
                    </template>
                </v-data-table>
                <h5 style="margin-top: 2rem;">Suma zamówienia: {{orderSum + ' zł'}}</h5>
            </v-card-text>
            <v-card-actions>
                <v-btn @click="goHome" text>
                    Powrót do strony głównej
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn @click="cancelOrder()" :disabled="orderStatusEn !== 'ordered'" v-bind:loading="loading" class="yellow_form_button" color="secondary">
                    Anuluj zamówienie
                </v-btn>
            </v-card-actions>
        </v-card>
        </v-col>
    </v-row>
</template>

<script>
  import {notification} from '../../Notifications.js';

  export default {
    name: "order-show",
    props: ['token'],
    data() {
      return {
        orderedItemsHeaders: [
          {text: 'Nazwa', value: 'name',},
          {text: 'Cena (zł)', value: 'price'},
          {text: 'Ilość', value: ''},
        ],
        orderedItems: [],
        orderStatus: '',
        orderStatusEn: '',
        orderSum: '',
        loading: false
      }
    },
    beforeMount() {
      this.getOrder();
    },
    methods: {
      getOrder() {
        axios.get(route('api.order.loadOrder', this.token))
          .then(response => {
            this.orderedItems = response.data.dishes;
            this.orderSum = response.data.sum.toFixed(2);
            this.orderStatus = response.data.status_pl
            this.orderStatusEn = response.data.status
            console.log(response)
          }).catch(error => {
          console.error(error)
        })
      },
      goHome() {
        window.location.href = route('home')
      },
      cancelOrder() {
        this.loading = true;
        let token = this.token;
        axios.delete(route('api.order.delete', {
          token: token
        }))
          .then(response => {
            notification("Zamóweinie anulowane", 'success');
            window.location.href = route('home')
          }).catch(error => {
          notification("Brak możliwości anulowania zamówienia", 'error');
          console.error(error.response);
        }).finally(() => {
          this.loading = false;
        });
      }
    }
  }
</script>

<style scoped>

</style>