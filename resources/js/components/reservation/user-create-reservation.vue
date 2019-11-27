<template>
  <v-row class="justify-center align-center">
    <v-col
        cols="12" lg="4" ma-2 md="5" sm="8" xl="3">
      <v-card class="transparent_form">
        <v-card-title>
          Formularz rezerwacji
        </v-card-title>
        <v-card-text>
          <v-select
              :items="personSelectItems"
              item-text="text"
              item-value="value"
              label="Wybierz ilość osób"
              outlined
              v-model="tableSize"
          ></v-select>
          <v-menu
              :close-on-content-click="false"
              :return-value.sync="date"
              min-width="290px"
              offset-y
              ref="menu"
              transition="scale-transition"
              v-model="menu"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                  append-icon="event"
                  label="Wybierz datę"
                  outlined
                  readonly
                  v-model="date"
                  v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker :min="setMinDate()" scrollable v-model="date">
              <v-spacer></v-spacer>
              <v-btn @click="menu = false" color="primary" text>Cancel</v-btn>
              <v-btn @click="$refs.menu.save(date)" color="primary" text>OK</v-btn>
            </v-date-picker>
          </v-menu>
          <v-menu
              :close-on-content-click="false"
              :nudge-right="40"
              :return-value.sync="time"
              max-width="290px"
              min-width="290px"
              offset-y
              ref="menu2"
              transition="scale-transition"
              v-model="menu2"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                  append-icon="access_time"
                  label="Wybierz godzinę"
                  outlined
                  readonly
                  v-model="time"
                  v-on="on"
              ></v-text-field>
            </template>
            <v-time-picker
                :allowed-hours="allowedHours"
                :allowed-minutes="allowedStep"
                :min="setMinTime()"
                @click:minute="$refs.menu2.save(time)"
                class="mt-4"
                format="24hr"
                full-width
                max="23:00"
                scrollable
                v-if="menu2"
                v-model="time"
            ></v-time-picker>
          </v-menu>
        </v-card-text>
        <v-card-actions class="justify-center">
          <v-btn @click="send" class="yellow_form_button" color="secondary" v-bind:loading="loading">Zarezerwuj</v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
  import {notificationError, notificationSuccess} from "../../Notifications";

  export default {
    name: "user-create-reservation",
    props: ['user'],
    data() {
      return {
        loading: false,
        personSelectItems: [
          {value: 1, text: 1},
          {value: 2, text: 2},
          {value: 3, text: 3},
          {value: 4, text: 4},
          {value: 5, text: 5},
          {value: 6, text: 6},
        ],
        time: null,
        menu2: false,
        menu: false,
        date: null,
        minDate: new Date().toISOString().substr(0, 10),
        email: '',
        phone: '',
        tableSize: null
      }
    },
    beforeMount() {
      this.email = this.user.email;
      this.phone = this.user.phone
    },
    methods: {
      allowedStep: m => m % 15 === 0,
      allowedHours: v => v >= 9 && v <= 23,
      setMinTime() {
        var d = new Date();
        var h = d.getHours();
        var m = d.getMinutes();
        h += 1;
        if (h + 1 <= 23 && d.toISOString().substr(0, 10) === this.date) {
          if (m > 0 && m < 15) {
            return h + ":15"
          } else if (m >= 15 && m < 30) {
            return h + ":30"
          } else if (m >= 30 && m < 45) {
            return h + ":45"
          } else {
            h += 1;
            return h + ":00"
          }
        } else {
          return "9:00"
        }
      },
      setMinDate() {
        var d = new Date();
        var h = d.getHours();
        var m = d.getMinutes();
        if (h > 23 || (h > 22 && m > 45)) {
          return (d.getDate() + 1).toISOString().substr(0, 10)
        } else {
          return this.minDate
        }
      },
      send() {
        if (this.date === null || this.time === null || this.tableSize === null) {
          notificationError("Musisz wypełnić wszystkie dane!!!");
        } else {
          this.loading = true;
          axios.post(route('api.reservation.storeAsCustomer'), {
            date: this.date,
            startTime: this.time,
            email: this.email,
            phone: this.phone,
            tableSize: this.tableSize,
          }).then(
            response => {
              notificationSuccess(response.data.message);
              setTimeout(function () {
                window.location.href = route('home')
              }, 2000);
            },
            error => {
              if (error.response.status === 422) {
                if (error.response.data.errors.date) {
                  notificationError(error.response.data.errors.date);
                } else {
                  notificationError("Podano niepoprawne dane, spróbuj jeszcze raz");
                }
              } else {
                notificationError(error.response.data);
              }

            },
          ).finally(() => {
            this.loading = false;
          });
        }
      }
    },
  }
</script>

<style scoped>

</style>