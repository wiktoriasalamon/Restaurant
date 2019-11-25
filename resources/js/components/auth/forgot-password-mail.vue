<template>
	<v-row class="justify-center align-center">
		<v-col
			cols="12" ld="4" ma-2 md="5" sm="8" xl="3">
			<v-card class="transparent_form">
				<v-text-field
					:rules="rules"
					label="Email"
					v-model="mail"
				></v-text-field>
				<v-card-actions class="justify-space-between">
					<v-btn @click="goBack" text>Wróć</v-btn>
					<v-btn @click="send" class="yellow_form_button" color="secondary">Wyślij</v-btn>
				</v-card-actions>
			</v-card>
		</v-col>
	</v-row>
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
          .then((response) => {
            notification(response.data, "success")
          })
          .catch(error => {
            notification(error.response.data, "error")
          });
      },
    }
  }
</script>

<style scoped>

</style>