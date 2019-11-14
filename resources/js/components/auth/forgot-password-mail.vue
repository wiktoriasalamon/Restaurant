<template>
  <v-card>
    <v-card-title>Resetowanie hasła</v-card-title>
    <v-card-text>
      <v-text-field
          :rules="rules"
          label="Email"
          v-model="mail"
      ></v-text-field>
    </v-card-text>
    <v-card-actions>
      <v-btn @click="goBack">Wróć</v-btn>
      <v-btn @click="send">Wyślij</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import {notification} from '../../Notifications.js';
  export default {
    name: "forgot-password-mail",
    data() {
      return {
        rules: [
          value => !!value || "To pole jest wymagane",
          v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Niepoprawny adres email'
        ],
        mail: '',
      };
    },
    methods: {
      goBack() {
        window.history.back();
      },
      send() {
        axios.post(route('password.email'), {
          'email': this.mail,
        })
          .then((response)=> {
            notification(response.data,"success")
          })
          .catch(error => {
            notification(error.response.data,"error")
           });
      },
    }
  }
</script>

<style scoped>

</style>