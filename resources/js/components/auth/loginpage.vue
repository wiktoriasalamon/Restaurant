<template>
	<div class="login-form">
		
		<v-text-field 
			label="e-mail" 
			outlined
			v-model="input.email"
			:rules="[rules.required, rules.email]"
		></v-text-field>
		<v-text-field 
			label="hasło" 
			outlined
			v-model="input.password"
			:append-icon="showPassword ? 'visibility' : 'visibility_off'"
			:type="showPassword ? 'text' : 'password'"
			:rules="[rules.required]"
			@click:append="showPassword = !showPassword"
		></v-text-field>
		<v-btn 
			text 
			small
			class="btn-forgot-password"
			@click="handlePressForgetPassword()"> 
			Nie pamiętam hasła 
		</v-btn>
		<div class="login-button">
			<v-btn large @click="handlePressLogin()">Zaloguj się</v-btn>
		</div>
		<v-snackbar
			v-model="snackbar.show"
		>
		{{ text }}
		<v-btn
			color="pink"
			text
			@click="snackbar.show = false"
		>
			Zamknij
		</v-btn>
		</v-snackbar>
		<v-btn 
			text  
			@click="handlePressRegister()"
			class="btn-register">
			Zarejestruj się
		</v-btn>
	</div>
</template>

<script>
import {isEmail} from '../../validator/DataValidator.js';

export default {
	name: "loginPage",

	data() {
		return {
			showPassword: false,
			formIsEmpty: true,
			formHasErrors: false,
			input: {
				email: "",
				password: "",
			},
			snackbar: {
				show: false,
			},
			text: 'Hello, I\'m a snackbar',
			rules: {
				required: value => {
					return !!value || 'Pole nie może być puste.';
				},
				email: value => {
					return isEmail(value) || 'Nieprawidłowy e-mail.';
          		},
			   },
		}
	},
	methods: {
		handlePressLogin() {
			if(this.input.email == "" || this.input.password == "") {
				this.showAlert('Pola nie mogą być puste');
			} else if (!isEmail(this.input.email)) {
				this.showAlert('Wprowadzono nieprawidłowe dane');
			} else {
				this.showAlert('Tu będzie logowanie');
			}
		},
		login() {
			axios.post('', {

			})
			.then((response)=> {

			})
			.catch();
		},
		handlePressRegister() {
			window.location.href = route('register');
		},
		handlePressForgetPassword() {
			this.showAlert('Tu będzie przypominanie hasła');
		},
		showAlert(alert) {
			this.text=alert;
			this.snackbar.show = true;
		}
	}
  }
</script>

<style scoped>
	.login-form {
		display: flex;
		flex-direction: column;
		float: center;
		margin: 20vh 30vw;
		background: rgba(255,255,255,0.5);
		padding: 2vw 5vw;
	}
	.btn-forgot-password {
		align-self: flex-end;
		text-transform: none;
		font-weight: normal;
	}
	.login-button {
		margin: 2vw 3vw;
		display: flex;
		flex-direction: column;
		float: center;
	}
	.btn-register {
		align-self: center
	}
</style>
