<template>
  <v-row class="justify-center align-center">
    <v-col cols="12" lg="5" md="8" sm="10" xl="4">
      <v-card class="transparent_form">
        <v-card-title>
          Rezerwacja
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col cols="12" lg="6" md="8" sm="10" xl="6">
              <p>Ilośc osób:</p>
              <p>Godzina rozpoczęcia:</p>
              <p>Data rezerwacji:</p>
            </v-col>
            <v-col cols="12" lg="6" md="8" sm="10" xl="6">
              <p>{{ form.tableSize }}</p>
              <p>{{ form.startTime }}</p>
              <p>{{ form.date }}</p>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-row class="justify-space-between">
            <v-btn @click="goBack" text>Wróć</v-btn>
            <v-btn @click="cancelReservation" class="yellow_form_button" color="secondary">Odwołaj rezerwację</v-btn>
          </v-row>

        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>

</template>

<script>
  import {notification} from "../../Notifications";

  export default {
    name: "user-show-reservation",
    props: ['id'],
    data() {
      return {
        form: {}
      };
    },
    beforeMount() {
      this.getData();
    },
    methods: {
      getData() {
        axios.get(route('api.reservation.showUser', this.id))
          .then(response => {
            this.form = response.data.reservation;
          })
          .catch(error => {
            notification(error.response.data, 'error');
          });
      },
      goBack() {
        window.location.replace(route('reservation.indexUser'));
      },
      cancelReservation() {
        axios.delete(route('api.reservation.delete', this.id)).then(response => {
          notification(response.data, 'success');
          window.location.replace(route('reservation.indexUser'));
        }).catch(error => {
          console.error(error);
          notification(error.response.data, 'error');
        })
      }
    }
  }
</script>

<style scoped>

</style>