<template>
	<div class="register-form">
		
		<v-text-field 
			label="Imię" 
			outlined
			v-model="input.firstName"
			:rules="[rules.required]"
		></v-text-field>
		<v-text-field 
			label="Nazwisko" 
			outlined
			v-model="input.lastName"
			:rules="[rules.required]"
		></v-text-field>
		<v-text-field 
			label="E-mail" 
			outlined
			v-model="input.email"
			:rules="[rules.required, rules.email]"
		></v-text-field>
		<v-text-field 
			label="Ulica" 
			outlined
			v-model="input.street"
			:rules="[rules.required]"
		></v-text-field>
		<v-text-field 
			label="Numer domu" 
			outlined
			v-model="input.homeNumber"
			:rules="[rules.required, rules.number]"
		></v-text-field>
		<v-text-field 
			label="Numer mieszkania" 
			outlined
			v-model="input.flatNumber"
			:rules="[rules.required, rules.number]"
		></v-text-field>
		<v-text-field 
			label="Miejscowość" 
			outlined
			v-model="input.city"
			:rules="[rules.required]"
		></v-text-field>
		<v-text-field 
			label="Hasło" 
			outlined
			v-model="input.password1"
			:append-icon="showPassword ? 'visibility' : 'visibility_off'"
			:type="showPassword1 ? 'text' : 'password'"
			:rules="[rules.required]"
			@click:append="showPassword1 = !showPassword1"
		></v-text-field>
		<v-text-field 
			label="Powtórz hasło" 
			outlined
			v-model="input.password2"
			:append-icon="showPassword ? 'visibility' : 'visibility_off'"
			:type="showPassword2 ? 'text' : 'password'"
			:rules="[rules.required]"
			@click:append="showPassword2 = !showPassword2"
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
import {isEmail, isPassword, isPhoneNumber, isPostalCode } from '../../validator/DataValidator.js';

export default {
	name: "loginPage",

	data() {
		return {
			showPassword1: false,
			showPassword2: false,
			input: {
				firstName: null,
				lastName: null,
				email: null,
				street: null,
				homeNumber: null,
				flatNumber: null,
				city: null,
				password1: null,
				password2: null
			},
			snackbar: {
				show: false,
			},
			text: "",
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
	.register-form {
		display: flex;
		flex-direction: column;
		float: center;
		margin: 10vh 30vw;
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
