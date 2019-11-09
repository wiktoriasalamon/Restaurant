<template>
	<div class="login-form">
		
		<v-text-field 
			label="E-mail" 
			outlined
			v-model="input.email"
			:rules="[rules.required, rules.email]"
		></v-text-field>
		<v-text-field 
			label="Hasło" 
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
			v-model="snackbarShow"
		>
		{{ text }}
		<v-btn
			color="pink"
			text
			@click="snackbarShow = false"
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
import alertStrings from '../../strings/AlertStrings.js';

export default {
	name: "loginPage",

	data() {
		return {
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
			if(this.input.email == "" || this.input.password == "") {
				this.showAlert(alertStrings.emptyField);
			} else if (!isEmail(this.input.email)) {
				this.showAlert(alertStrings.invalidData);
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
			this.snackbarShow = true;
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
