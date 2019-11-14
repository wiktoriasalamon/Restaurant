<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
  <v-container>
    <v-layout align-start justify-center pt-5 row wrap>
      <v-flex xs12 sm12 md12 lg8 xl8>
        <v-data-table
          :headers="headers"
          :items="orders"
          :loading="isLoading"
          :no-data-text="nodata"
        >
          <template slot="item" slot-scope="props">
            <tr>
              <td class="text-xs-left">{{props.item.id}}</td>
              <td class="text-xs-left">{{props.item.status}}</td>
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
      </v-flex>
      <v-flex xs12 sm12 md12 lg4 xl4>
        <v-card>
          <v-card-title>Stolik {{table}}</v-card-title>
          <v-card-text>
            <span> Stolik zajęty od: {{occupiedSince}}</span> <br/>
            <span v-if="reservationSince"> Zarezerwowany od: {{reservationSince}}</span>
            <span v-else> Brak rezerwacji</span>

          </v-card-text>
          <v-card-actions>
            <v-btn v-if="occupiedSince"@click="closeTable" text>
              Zamknij stolik
            </v-btn>
            <v-btn v-else @click="openTable" text>
              Otwórz stolik
            </v-btn>
            <v-btn @click="addOrder" text>
              Dodaj zamówienie
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
  import {notification} from '../../Notifications.js';

  export default {
    props: ["id"],
    data() {
      return {
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
        occupiedSince:'',
        reservationSince:'',
        table:''

      }
    },
    beforeMount() {
      this.getData()
    },
    methods: {
      getData() {
        axios.get(route('api.table.loadTableForWaiter', this.id)).then(response => {
          this.orders = response.data.table.order
          this.occupiedSince = response.data.table.occupied_since
          this.table = response.data.table.id
          this.reservationSince = response.data.reservation_since
          this.isLoading = false
        }).catch(error => {
          this.isLoading = false
          notification("Wystąpił błąd podczas pobierania danych", "error")
        });
      },
      editOrder(token) {
        window.location.href = route("", token) //todo: podpiąć
      },
      showOrder(token) {
        window.location.href = route("", token)
      },
      openTable(){
        axios.post(route('api.table.openTable',this.table)).then(
          response => {
            notification("Stolik został otwarty","success")
            this.getData()
          },
          error => {
            notification("Wystąpił błąd podczas otwierania stolika","error")
          })
      },
      closeTable(){
        axios.post(route('api.table.closeTable',this.table)).then(
          response => {
            notification("Stolik został zamknięty","success")
            setTimeout(function(){
              window.location.href=route('table.waiterIndex')} , 1500);
          },
          error => {
            notification("Wystąpił błąd podczas zamykania stolika","error")
          })
      },
      addOrder(){
        window.location.href = route("order.createWaiter", this.table)
      }
    }
  }
</script>
<style scoped>

</style>