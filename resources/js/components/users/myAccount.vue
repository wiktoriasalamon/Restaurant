<template>
  <v-row class="justify-space-around">
    <v-col
        cols="12" lg="4" ma-2 md="5" sm="8" xl="3">
      <v-card class="transparent_form">
        <v-card-title>
          Moje konto
        </v-card-title>
        <v-card-text>
          <v-form
              ref="accountForm">
            <v-text-field
                :rules="[rules.required]"
                label="Imię"
                outlined
                v-bind:error-messages="errors.name"
                v-model="form.name">
            </v-text-field>
            <v-text-field
                :rules="[rules.required]"
                label="Nazwisko"
                outlined
                v-bind:error-messages="errors.surname"
                v-model="form.surname">
            </v-text-field>
            <v-text-field
                :rules="[rules.required, rules.emailRules]"
                label="E-mail"
                outlined
                v-bind:error-messages="errors.email"
                v-model="form.email">
            </v-text-field>
            <v-text-field
                label="Numer telefonu"
                outlined
                v-bind:error-messages="errors.phone"
                v-model="form.phone">
            </v-text-field>
            <v-text-field
                :rules="[rules.required]"
                label="Ulica"
                outlined
                v-bind:error-messages="errors.address.street"
                v-model="form.address.street">
            </v-text-field>
            <v-text-field
                :rules="[rules.required]"
                label="Numer domu "
                outlined
                v-bind:error-messages="errors.address.houseNumber"
                v-model="form.address.houseNumber">
            </v-text-field>
            <v-text-field
                label="Numer mieszkania"
                outlined
                v-bind:error-messages="errors.address.apartmentNumber"
                v-model="form.address.apartmentNumber">
            </v-text-field>
            <v-text-field
                :rules="[rules.required]"
                label="Miejscowość"
                outlined
                v-bind:error-messages="errors.address.city"
                v-model="form.address.city">
            </v-text-field>
            <v-text-field
                :rules="[rules.required, rules.postCodeFormat]"
                label="Kod pocztowy"
                outlined
                v-bind:error-messages="errors.address.postCode"
                v-model="form.address.postCode">
            </v-text-field>
            <v-row class="justify-center">
              <v-btn @click="save" class="yellow_form_button" color="secondary" v-bind:loading="loadingForm">
                Zapisz
              </v-btn>
            </v-row>
          </v-form>
        </v-card-text>
      </v-card>
    </v-col>
    <v-col
        cols="12" lg="4" ma-2 md="5" sm="8" xl="3">
      <v-card class="transparent_form">
        <v-card-title>
          <h1>Zmiana hasła</h1>
        </v-card-title>
        <v-card-text>
          <v-form
              ref="passwordForm">
            <v-text-field
                :append-icon="show1 ? 'visibility' : 'visibility_off'"
                :rules="[rules.required, rules.min6]"
                :type="show1 ? 'text' : 'password'"
                @click:append="show1 = !show1"
                label="Stare hasło"
                outlined
                v-bind:error-messages="passwordErrors.oldPassword"
                v-model="passwordForm.oldPassword">
            </v-text-field>
            <v-text-field
                :append-icon="show2 ? 'visibility' : 'visibility_off'"
                :rules="[rules.required, rules.min6]"
                :type="show2 ? 'text' : 'password'"
                @click:append="show2 = !show2"
                label="Nowe hasło"
                outlined
                v-bind:error-messages="passwordErrors.newPassword"
                v-model="passwordForm.newPassword">
            </v-text-field>
            <v-text-field
                :append-icon="show3 ? 'visibility' : 'visibility_off'"
                :rules="[rules.required, rules.min6, rules.passwordRules]"
                :type="show3 ? 'text' : 'password'"
                @click:append="show3 = !show3"
                label="Powtórz hasło"
                outlined
                v-bind:error-messages="passwordErrors.newPasswordRepeated"
                v-model="passwordForm.newPasswordRepeated">
            </v-text-field>
            <v-row class="justify-center">
              <v-btn @click="savePassword" class="yellow_form_button" color="secondary"
                     v-bind:loading="loadingPassword">
                Zapisz
              </v-btn>
            </v-row>

          </v-form>
        </v-card-text>
      </v-card>

    </v-col>
  </v-row>
</template>

<script>
  import {notificationError, notificationSuccess} from "../../Notifications";

  export default {
    name: "myAccount",
    data() {
      return {
        loadingPassword: false,
        loadingForm: false,
        form: {
          id: '',
          name: '',
          surname: '',
          email: '',
          address: {
            street: '',
            houseNumber: '',
            apartmentNumber: '',
            postCode: '',
            city: '',
          },
          phone: '',
        },
        passwordForm: {
          oldPassword: '',
          newPassword: '',
          newPasswordRepeated: ''
        },
        errors: {
          name: '',
          surname: '',
          email: '',
          address: {
            street: '',
            houseNumber: '',
            apartmentNumber: '',
            postCode: '',
            city: '',
          },
          phone: '',
        },
        passwordErrors: {
          oldPassword: '',
          newPassword: '',
          newPasswordRepeated: ''
        },
        rules: {
          required: value => !!value || "To pole jest wymagane",
          emailRules: v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Niepoprawny adres email',
          postCodeFormat: value => /^\d{2}-\d{3}$/.test(value) || 'Nieprawidłowy format kodu pocztowego',
          min6: v => v.length >= 6 || 'Hasło musi mieć conajmniej 6 znaków',
          passwordRules: value => ((value && !value.localeCompare(this.passwordForm.newPassword)) || "Hasła nie są takie same"),
        },
        show1: false,
        show2: false,
        show3: false,
      }
    },
    beforeMount() {
      this.getData()
    },
    methods: {
      getData() {
        axios.get(route('api.user.authenticatedUser'))
          .then(response => {
            const entries = Object.entries(response.data);
            if (response.data) {
              for (let [key, value] of entries) {
                if (key === 'address') {
                  let address = Object.entries(JSON.parse(value));
                  for (let [addressKey, addressValue] of address) {
                    this.form.address[addressKey] = addressValue
                  }
                } else {
                  this.form[key] = value;
                }
              }
            }
          }).catch(error => {
          console.error(error)
        })
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
      fillErrors(error, errorsObject) {
        this.clearErrors(errorsObject);
        let entries = Object.entries(error.response.data.errors);
        for (let [key, value] of entries) {
          if (key.includes('address')) {
            let realKey = key.split('.')[1];
            errorsObject.address[realKey] = value;
          } else {
            errorsObject[key] = value;
          }
        }
      },
      save() {
        if (this.$refs.accountForm.validate() !== true) {
          return;
        }
        this.loadingForm = true;
        axios.put(route('api.user.updateUserMyAccount', this.form.id), {
          name: this.form.name,
          surname: this.form.surname,
          address: this.form.address,
          phone: this.form.phone,
          email: this.form.email,
        }).then(
          response => {
            notificationSuccess(response.data.message);
            window.location.href = route('home');
          }).catch(error => {
          console.log(error.response);
          if (error.response.status === 422) {
            notificationError("Podano niepoprawne dane, spróbuj jeszcze raz");
            this.fillErrors(error, this.errors);
          } else {
            notificationError(error.response.data);
          }
        }).finally(() => {
          this.loadingForm = false;
        });
      },
      savePassword() {
        if (this.$refs.passwordForm.validate() !== true) {
          return;
        }
        this.loadingPassword = true;
        axios.put(route('api.user.changePasswordMyAccount', this.form.id), {
          oldPassword: this.passwordForm.oldPassword,
          newPassword: this.passwordForm.newPassword,
          newPasswordRepeated: this.passwordForm.newPasswordRepeated,
        }).then(
          response => {
            notificationSuccess(response.data.message);
            window.location.href = route('home');
          }).catch(
          error => {
            console.log(error.response);
            if (error.response.status === 422) {
              notificationError("Podano niepoprawne dane, spróbuj jeszcze raz");
              this.fillErrors(error, this.passwordErrors);
            } else {
              notificationError(error.response.data.message);
            }
          }).finally(() => {
          this.loadingPassword = false;
        })
      }
    }
  }
</script>

<style scoped>

</style>

