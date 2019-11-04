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
		<a class="a-forgot-password"> Nie pamiętam hasła </a>
		<div class="login-button">
			<v-btn large @click="login()">Zaloguj się</v-btn>
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
		<a class="a-register">Zarejestruj się</a>
	</div>
</template>

<script>
import {isEmail, isPassword, isPhoneNumber, isPostalCode } from '../../validator/DataValidator.js';

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
		login() {
			if(this.input.email == "" || this.input.password == "") {
				this.text='Pola nie mogą być puste';
				this.snackbar.show = true;
			} else if (!isEmail(this.input.email)) {
				this.text='Wprowadzono nieprawidłowe dane';
				this.snackbar.show = true;
			} else {
				this.text='Wszystko ok';
				this.snackbar.show = true;
			}
		},
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
	.a-forgot-password {
		align-self: flex-end;
	}
	.login-button {
		margin: 2vw 3vw;
		display: flex;
		flex-direction: column;
		float: center;
	}
	.a-register {
		align-self: center
	}
</style>
