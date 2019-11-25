<template>
  <v-card md="6">
    <v-card-title>
      Rezerwacja
    </v-card-title>
    <v-card-text>
      <v-row md="4">
        <v-col md="2">
          <p>Ilośc osób:</p>
          <p>Godzina rozpoczęcia:</p>
          <p>Data rezerwacji:</p>
        </v-col>
        <v-col md="2">
          <p>{{ form.tableSize }}</p>
          <p>{{ form.startTime }}</p>
          <p>{{ form.date }}</p>
        </v-col>
      </v-row>
    </v-card-text>
    <v-card-actions>Ż
      <v-btn @click="goBack">Wróć</v-btn>
      <v-btn @click="cancelReservation">Odwołaj rezerwację</v-btn>
    </v-card-actions>
  </v-card>
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