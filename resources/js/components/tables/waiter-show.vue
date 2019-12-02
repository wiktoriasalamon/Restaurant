<template>
  <v-row class="justify-space-around align-center">
    <v-col cols="12" lg="6" md="10" sm="12" xl="5">
       <v-card class="component-header">
        <v-card-title>
          <h1>Zamówienia dla stolika nr {{table}}</h1>
        </v-card-title>
      </v-card>
      <v-data-table
          :headers="headers"
          :items="orders"
          :loading="isLoading"
          :no-data-text="nodata"
      >
        <template slot="item" slot-scope="props">
          <tr>
            <td class="text-xs-left">{{props.item.id}}</td>
            <td class="text-xs-left">{{props.item.status_pl}}</td>
            <td class="text-xs-left">{{props.item.created_at}}</td>
            <td class="text-xs-left">{{props.item.updated_at}}</td>
            <td class="text-xs-left">
              <v-icon @click="editOrder(props.item.token)" small>
                edit
              </v-icon>
              <v-icon @click="showOrder(props.item.token)" small>
                visibility
              </v-icon>
            </td>
          </tr>
        </template>
      </v-data-table>
    </v-col>
    <v-col cols="12" lg="5" md="8" sm="10" xl="4">
      <v-card class="transparent_form">
        <v-card-title>Stolik {{table}}</v-card-title>
        <v-card-text>
          <span> Stolik zajęty od: {{occupiedSince}}</span> <br/>
          <span v-if="reservationSince"> Zarezerwowany od: {{reservationSince}}</span>
          <span v-else> Brak rezerwacji</span>

        </v-card-text>
        <v-card-actions>
          <v-row wrap>
            <v-btn @click="closeTable" class="yellow_form_button" color="secoDndary" v-bind:loading="loadingButton"
                   v-if="occupiedSince">
              Zamknij stolik
            </v-btn>
            <v-btn @click="openTable" class="yellow_form_button" color="secondary" v-bind:loading="loadingButton"
                   v-else>
              Otwórz stolik
            </v-btn>
            <v-btn @click="addOrder" class="yellow_form_button" color="secondary" v-if="occupiedSince">
              Dodaj zamówienie
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
    props: ["id"],
    data() {
      return {
        loadingButton: false,
        isLoading: true,
        headers: [
          {text: 'Numer zamówienia', align: 'center', value: 'token'},
          {text: 'Status', align: 'center', value: 'status'},
          {text: 'Utworzono', align: 'center', value: 'created_at'},
          {text: 'Edytowano', align: 'center', value: 'updated_at'},
          {text: 'Akcje', align: 'center', value: ''}
        ],
        orders: [],
        nodata: "Brak danych",
        occupiedSince: '',
        reservationSince: '',
        table: ''

      }
    },
    beforeMount() {
      this.getData()
    },
    created() {
      Echo.channel('table')
        .listen('TableChanged', (e) => {
          this.getData()
        });
      Echo.channel('order')
        .listen('OrderChanged', (e) => {
          this.getData()
        });
      Echo.channel('reservation')
        .listen('ReservationChanged', (e) => {
          this.getData()
        });

    },
    methods: {
      getData() {
        axios.get(route('api.table.loadTableForWaiter', this.id)).then(response => {
          this.orders = response.data.table.order;
          this.occupiedSince = response.data.table.occupied_since;
          this.table = response.data.table.id;
          this.reservationSince = response.data.reservation_since;
          this.isLoading = false
        }).catch(error => {
          this.isLoading = false;
          notification("Wystąpił błąd podczas pobierania danych", "error")
        });
      },
      editOrder(token) {
        window.location.href = route('order.editWaiter', [token])
      },
      showOrder(token) {
        window.location.href = route('order.show', [token])
      },
      openTable() {
        this.loadingButton = true;
        axios.post(route('api.table.openTable', this.table)).then(
          response => {
            notification("Stolik został otwarty", "success");
            this.getData()
          },
          error => {
            notification("Wystąpił błąd podczas otwierania stolika", "error")
          }).finally(() => {
          this.loadingButton = false;
        })
      },
      closeTable() {
        this.loadingButton = true;
        axios.post(route('api.table.closeTable', this.table)).then(
          response => {
            notification("Stolik został zamknięty", "success");
            setTimeout(function () {
              window.location.href = route('table.waiterIndex')
            }, 1500);
          },
          error => {
            notification("Wystąpił błąd podczas zamykania stolika", "error")
          }).finally(() => {
          this.loadingButton = false;
        })
      },
      addOrder() {
        window.location.href = route("order.createWaiter", this.table)
      }
    }
  }
</script>
<style scoped>

</style>