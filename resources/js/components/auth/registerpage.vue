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
			label="Numer telefonu" 
			outlined
			v-model="input.phoneNumber"
			:rules="[rules.required, rules.phoneNumber]"
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
			:rules="[rules.required]"
		></v-text-field>
		<v-text-field 
			label="Numer mieszkania" 
			outlined
			v-model="input.flatNumber"
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
			:append-icon="showPassword1 ? 'visibility' : 'visibility_off'"
			:type="showPassword1 ? 'text' : 'password'"
			:rules="[rules.required, rules.password]"
			@click:append="showPassword1 = !showPassword1"
		></v-text-field>
		<v-text-field 
			label="Powtórz hasło" 
			outlined
			v-model="input.password2"
			:append-icon="showPassword2 ? 'visibility' : 'visibility_off'"
			:type="showPassword2 ? 'text' : 'password'"
			:rules="[rules.required, rules.passwordMatch]"
			@click:append="showPassword2 = !showPassword2"
		></v-text-field>
		<div class="register-button">
			<v-btn large @click="handlePressRegister()">Zarejestruj się</v-btn>
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
			@click="handlePressLogin()"
			class="btn-login">
			Masz już konto? Zaloguj się!
		</v-btn>
	</div>
</template>

<script>
import {isEmail, isPassword, isPhoneNumber, passwordMatch} from '../../validator/DataValidator.js';
import alertStrings from '../../strings/AlertStrings.js';

export default {
	name: "loginPage",

	data() {
		return {
			showPassword1: false,
			showPassword2: false,
			input: {
				firstName: "",
				lastName: "",
				email: "",
				phoneNumber: "",
				street: "",
				homeNumber: "",
				flatNumber: "",
				city: "",
				password1: "",
				password2: ""
			},
			snackbar: {
				show: false,
			},
			text: "",
			rules: {
				required: value => {
					return !!value || alertStrings.emptyField;
				},
				email: value => {
					return isEmail(value) || alertStrings.invalidEmail;
				  },
				phoneNumber: value => {
					return isPhoneNumber(value) || alertStrings.invalidPhoneNumber;
				},
				password: value => {
					return isPassword(value) || alertStrings.invalidPassword;
				},
				passwordMatch: value => {
					return passwordMatch(this.input.password1,value) || alertStrings.differentPasswords;
				}
			   },
		}
	},
	methods: {
		handlePressRegister() {
			if(this.validateData()) {
				this.showAlert('Tu będzie rejestracja');
			}
		},
		register() {
			axios.post('', {

			})
			.then((response)=> {

			})
			.catch();
		},
		handlePressLogin() {
			window.location.href = route('login');
		},
		showAlert(alert) {
			this.text=alert;
			this.snackbar.show = true;
		},
		validateData() {
			if(this.input.firstName=="" || 
			this.input.lastName=="" ||
			this.input.email=="" ||
			this.input.phoneNumber=="" ||
			this.input.street=="" ||
			this.input.homeNumber=="" ||
			this.input.city=="" ||
			this.input.password1=="" || 
			this.input.password2=="") {
				this.showAlert(alertStrings.emptyField);
			} else if (!isEmail(this.input.email)) {
				this.showAlert(alertStrings.invalidEmail);
			} else if (!isPhoneNumber(this.input.phoneNumber)) {
				this.showAlert(alertStrings.invalidPhoneNumber);
			} else if (!isPassword(this.input.password1)) {
				this.showAlert(alertStrings.invalidPassword);
			} else if (!passwordMatch(this.input.password1, this.input.password2)) {
				this.showAlert(alertStrings.differentPasswords);
			} else {
				return true;
			}
			return false;
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
	.register-button {
		margin: 2vw 3vw;
		display: flex;
		flex-direction: column;
		float: center;
	}
	.btn-login {
		align-self: center
	}
</style>
