<template>
	<v-row class="justify-center align-center">
		<v-col
			cols="12" ld="4" ma-2 md="5" sm="8" xl="3">
			<v-card class="transparent_form">
				<v-text-field
					:rules="[rules.required]"
					label="Imię"
					outlined
					v-model="input.firstName"
				></v-text-field>
				<v-text-field
					:rules="[rules.required]"
					label="Nazwisko"
					outlined
					v-model="input.lastName"
				></v-text-field>
				<v-text-field
					:rules="[rules.required, rules.email]"
					label="E-mail"
					outlined
					v-model="input.email"
				></v-text-field>
				<v-text-field
					:rules="[rules.required, rules.phoneNumber]"
					label="Numer telefonu"
					outlined
					prefix="+48"
					v-model="input.phoneNumber"
				></v-text-field>
				<v-text-field
					:rules="[rules.required]"
					label="Ulica"
					outlined
					v-model="input.address.street"
				></v-text-field>
				<v-text-field
					:rules="[rules.required]"
					label="Numer domu"
					outlined
					v-model="input.address.homeNumber"
				></v-text-field>
				<v-text-field
					label="Numer mieszkania"
					outlined
					v-model="input.address.flatNumber"
				></v-text-field>
				<v-text-field
					:rules="[rules.required]"
					label="Miejscowość"
					outlined
					v-model="input.address.city"
				></v-text-field>
				<v-text-field
					:rules="[rules.required, rules.postCodeFormat]"
					label="Kod pocztowy"
					outlined
					v-model="input.address.postCode">

				</v-text-field>
				<v-text-field
					:append-icon="showPassword1 ? 'visibility' : 'visibility_off'"
					:rules="[rules.required, rules.password]"
					:type="showPassword1 ? 'text' : 'password'"
					@click:append="showPassword1 = !showPassword1"
					label="Hasło"
					outlined
					v-model="input.password1"
				></v-text-field>
				<v-text-field
					:append-icon="showPassword2 ? 'visibility' : 'visibility_off'"
					:rules="[rules.required, rules.passwordMatch]"
					:type="showPassword2 ? 'text' : 'password'"
					@click:append="showPassword2 = !showPassword2"
					label="Powtórz hasło"
					outlined
					v-model="input.password2"
				></v-text-field>
				<v-card-actions class="justify-center row">
					<v-btn @click="handlePressRegister()" class="yellow_form_button" color="secondary" large>Zarejestruj się</v-btn>
					<v-btn
						@click="handlePressLogin()"
						class="btn-login"
						text>
						Masz już konto? Zaloguj się!
					</v-btn>
				</v-card-actions>
			</v-card>
			<v-snackbar
				v-model="snackbar.show"
			>
				{{ text }}
				<v-btn
					@click="snackbar.show = false"
					color="pink"
					text
				>
					Zamknij
				</v-btn>
			</v-snackbar>

		</v-col>
	</v-row>

</template>

<script>
  import {isEmail, isPassword, isPhoneNumber, isPostalCode, passwordMatch} from '../../validator/DataValidator.js';
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
          address: {
            street: "",
            homeNumber: "",
            flatNumber: "",
            city: "",
            postCode: ''
          },
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
            return passwordMatch(this.input.password1, value) || alertStrings.differentPasswords;
          },
          postCodeFormat: value => {
            return isPostalCode(value) || alertStrings.invalidPostalCode
          }
        },
      }
    },
    methods: {
      handlePressRegister() {
        if (this.validateData()) {
          this.showAlert('Tu będzie rejestracja');
          this.register()
        }
      },
      register() {
        let address = JSON.stringify(this.input.address);
        axios.post('/api/user/store-customer', {
          name: this.input.firstName,
          surname: this.input.lastName,
          email: this.input.email,
          address: address,
          phone: this.input.phoneNumber,
          password: this.input.password1,
          repeatPassword: this.input.password2
        }).then(
          response => {
          },
          error => {

          })
      },
      handlePressLogin() {
        window.location.href = route('login');
      },
      showAlert(alert) {
        this.text = alert;
        this.snackbar.show = true;
      },
      validateData() {
        if (this.input.firstName == "" ||
          this.input.lastName == "" ||
          this.input.email == "" ||
          this.input.phoneNumber == "" ||
          this.input.street == "" ||
          this.input.homeNumber == "" ||
          this.input.city == "" ||
          this.input.password1 == "" ||
          this.input.password2 == "") {
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

</style>
