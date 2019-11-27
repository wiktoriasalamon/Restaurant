<template>
	<v-row class="justify-center align-center">
		<v-col
			cols="12" lg="4" ma-2 md="5" sm="8" xl="3">
			<v-card class="transparent_form">
				<v-text-field
					:rules="[rules.required, rules.email]"
					label="E-mail"
					outlined
					v-model="input.email"
				></v-text-field>
				<v-text-field
					:append-icon="showPassword ? 'visibility' : 'visibility_off'"
					:rules="[rules.required]"
					:type="showPassword ? 'text' : 'password'"
					@click:append="showPassword = !showPassword"
					label="Hasło"
					outlined
					v-model="input.password"
				></v-text-field>
				<v-card-actions class="row">
					<v-row class="justify-end forgot_button">
						<v-btn
							@click="handlePressForgetPassword()"
							small
							text>
							Nie pamiętam hasła
						</v-btn>
					</v-row>
					<v-row class="justify-center" style="width: 100%">
						<v-btn v-bind:loading="loading" @click="handlePressLogin()" class="yellow_form_button" color="secondary" large>Zaloguj się</v-btn>
					</v-row>
					<v-row class="justify-center register_button">
						<v-btn
							@click="handlePressRegister()"
							text>
							Zarejestruj się
						</v-btn>
					</v-row>
				</v-card-actions>
			</v-card>
		</v-col>
	</v-row>
</template>

<script>
  import {isEmail} from '../../validator/DataValidator.js';
  import alertStrings from '../../strings/AlertStrings.js';

  export default {
    name: "loginPage",

    data() {
      return {
      	loading: false,
        showPassword: false,
        input: {
          email: "",
          password: "",
        },
        snackbarShow: false,
        text: "",
        rules: {
          required: value => {
            return !!value || alertStrings.emptyField;
          },
          email: value => {
            return isEmail(value) || alertStrings.invalidEmail;
          },
        },
      }
    },
    methods: {
      handlePressLogin() {
        if (this.input.email === "" || this.input.password === "") {
          Vue.toasted.error(alertStrings.emptyField);
        } else if (!isEmail(this.input.email)) {
          Vue.toasted.error(alertStrings.invalidData);
        } else {
          this.login()
        }
      },
      login() {
        this.loading = true;
        axios.post('/login', {
          'email': this.input.email,
          'password': this.input.password,
        })
          .then((response) => {
            window.location.href = "/"
          })
          .catch(error => {
            if (error.response.status === 422) {
              Vue.toasted.error("Podano niepoprawne dane, spróbuj jeszcze raz").goAway(3000);
            } else {
              Vue.toasted.error(error.response.data).goAway(3000);
            }
          })
          .finally(() => {
            this.loading = false;
          });
      },
      handlePressRegister() {
        window.location.href = route('register');
      },
      handlePressForgetPassword() {
        window.location.href = route('password.request')
      },
      showAlert(alert) {
        this.text = alert;
        this.snackbarShow = true;
      }
    }
  }
</script>

<style scoped>
	.forgot_button {
		margin-top: -1rem;
		margin-bottom: 1rem;
	}
	.register_button{
		margin-top: 1rem;
	}

</style>
