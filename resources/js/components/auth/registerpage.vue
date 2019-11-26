<template>
  <v-row class="justify-center align-center">
    <v-col
        cols="12" ld="4" ma-2 md="5" sm="8" xl="3">
      <v-card class="transparent_form">
        <v-form
            ref="form">
          <v-text-field
              :rules="[rules.required]"
              label="Imię"
              outlined
              v-bind:error-messages="errors.name"
              v-model="input.name"
          ></v-text-field>
          <v-text-field
              :rules="[rules.required]"
              label="Nazwisko"
              outlined
              v-bind:error-messages="errors.surname"
              v-model="input.surname"
          ></v-text-field>
          <v-text-field
              :rules="[rules.required, rules.email]"
              label="E-mail"
              outlined
              v-bind:error-messages="errors.email"
              v-model="input.email"
          ></v-text-field>
          <v-text-field
              :rules="[rules.phoneNumber]"
              label="Numer telefonu"
              outlined
              prefix="+48"
              v-bind:error-messages="errors.phone"
              v-model="input.phone"
          ></v-text-field>
          <v-text-field
              :rules="[rules.required]"
              label="Ulica"
              outlined
              v-bind:error-messages="errors.address.street"
              v-model="input.address.street"
          ></v-text-field>
          <v-text-field
              :rules="[rules.required]"
              label="Numer domu"
              outlined
              v-bind:error-messages="errors.address.houseNumber"
              v-model="input.address.houseNumber"
          ></v-text-field>
          <v-text-field
              label="Numer mieszkania"
              outlined
              v-bind:error-messages="errors.address.flatNumber"
              v-model="input.address.flatNumber"
          ></v-text-field>
          <v-text-field
              :rules="[rules.required]"
              label="Miejscowość"
              outlined
              v-bind:error-messages="errors.address.city"
              v-model="input.address.city"
          ></v-text-field>
          <v-text-field
              :rules="[rules.required, rules.postCodeFormat]"
              label="Kod pocztowy"
              outlined
              v-bind:error-messages="errors.address.postCode"
              v-model="input.address.postCode"
          >
          </v-text-field>
          <v-text-field
              :append-icon="showPassword1 ? 'visibility' : 'visibility_off'"
              :rules="[rules.required, rules.password]"
              :type="showPassword1 ? 'text' : 'password'"
              @click:append="showPassword1 = !showPassword1"
              label="Hasło"
              outlined
              v-bind:error-messages="errors.password"
              v-model="input.password"
          ></v-text-field>
          <v-text-field
              :append-icon="showPassword2 ? 'visibility' : 'visibility_off'"
              :rules="[rules.required, rules.passwordMatch]"
              :type="showPassword2 ? 'text' : 'password'"
              @click:append="showPassword2 = !showPassword2"
              label="Powtórz hasło"
              outlined
              v-bind:error-messages="errors.repeatPassword"
              v-model="input.repeatPassword"
          ></v-text-field>
        </v-form>
        <v-card-actions class="justify-center row">
          <v-btn @click="handlePressRegister()" class="yellow_form_button" color="secondary" large
                 v-bind:loading="loading">Zarejestruj
            się
          </v-btn>
          <v-btn
              @click="handlePressLogin()"
              class="btn-login"
              text>
            Masz już konto? Zaloguj się!
          </v-btn>
        </v-card-actions>
      </v-card>

    </v-col>
  </v-row>

</template>

<script>
  import {isEmail, isPassword, isPhoneNumber, isPostalCode, passwordMatch} from '../../validator/DataValidator.js';
  import alertStrings from '../../strings/AlertStrings.js';
  import {notificationError, notificationSuccess} from "../../Notifications";

  export default {
    name: "loginPage",

    data() {
      return {
        loading: false,
        showPassword1: false,
        showPassword2: false,
        input: {
          name: "",
          surname: "",
          email: "",
          phone: "",
          address: {
            street: "",
            houseNumber: "",
            flatNumber: "",
            city: "",
            postCode: ''
          },
          password: "",
          repeatPassword: ""
        },
        errors: {
          name: [],
          surname: [],
          email: [],
          phone: [],
          address: {
            street: [],
            houseNumber: [],
            flatNumber: [],
            city: [],
            postCode: []
          },
          password: [],
          repeatPassword: []
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
            return passwordMatch(this.input.password, value) || alertStrings.differentPasswords;
          },
          postCodeFormat: value => {
            return isPostalCode(value) || alertStrings.invalidPostalCode
          }
        },
      }
    },
    methods: {
      handlePressRegister() {
        if (this.$refs.form.validate()) {
          this.clearErrors(this.errors);
          this.register()
        }
      },
      clearErrors(object) {
        let keys = Object.keys(object);
        for (let key of keys) {
          if (key === 'address') {
            this.clearErrors(object[key]);
          } else {
            object[key] = [];
          }
        }
      },
      fillErrors(error) {
        this.clearErrors(this.errors);
        let entries = Object.entries(error.response.data.errors);
        for (let [key, value] of entries) {
          if (key.includes('address')) {
            let realKey = key.split('.')[1];
            this.errors.address[realKey] = value;
          } else {
            this.errors[key] = value;
          }
        }
      },
      register() {
        this.loading = true;
        axios.post('/api/user/store-customer', this.input).then(
          response => {
            notificationSuccess(response.data.message)
            window.location.replace(route('login'))
          }).catch(error => {
          if (error.response.statusCode === 500) {
            notificationError(error.response.data.message);
          } else {
            notificationError('Wystąpił błąd podczas rejestracji');
            this.fillErrors(error);
          }
        }).finally(() => {
          this.loading = false;
        });
      },
      handlePressLogin() {
        window.location.href = route('login');
      },
    }
  }
</script>

<style scoped>

</style>
