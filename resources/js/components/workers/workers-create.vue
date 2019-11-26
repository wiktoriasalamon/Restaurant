<template>
  <v-card>
    <v-card-title>Dodawanie pracownika</v-card-title>
    <v-card-text>
      <v-container>
        <v-form
            ref="form"
        >
          <v-text-field :rules="[rules.required]" label="Imię" v-bind:error-messages="errors.name"
                        v-model="form.name"></v-text-field>
          <v-text-field :rules="[rules.required]" label="Nazwisko" v-bind:error-messages="errors.surname"
                        v-model="form.surname"></v-text-field>
          <v-text-field :rules="[rules.required, rules.emailRules]" label="Email" v-bind:error-messages="errors.email"
                        v-model="form.email"></v-text-field>
          <v-text-field :rules="[rules.required]" label="Ulica" v-bind:error-messages="errors.address.street"
                        v-model="form.address.street"></v-text-field>
          <v-text-field label="Numer domu" :rules="[rules.required]"
                        v-bind:error-messages="errors.address.houseNumber"
                        v-model="form.address.houseNumber"></v-text-field>
          <v-text-field label="Numer apartamentu" :rules="[rules.required]"
                        v-bind:error-messages="errors.address.apartmentNumber"
                        v-model="form.address.apartmentNumber"></v-text-field>
          <v-text-field :rules="[rules.required]" label="Miasto" v-bind:error-messages="errors.address.city"
                        v-model="form.address.city"></v-text-field>
          <v-text-field label="Kod pocztowy" :rules="[rules.required, rules.postCodeFormat]"
                        v-bind:error-messages="errors.address.postCode" v-model="form.address.postCode"></v-text-field>
          <v-text-field :rules="[rules.phoneMax12]" v-bind:error-messages="errors.phone"
                        label="Telefon" v-model="form.phone"></v-text-field>
          <v-text-field :rules="[rules.required, rules.passwordMax6]" label="Hasło"
                        v-bind:error-messages="errors.password"
                        v-model="form.password"></v-text-field>
        </v-form>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-btn @click="cancel">Anuluj</v-btn>
      <v-btn v-bind:loadin="loading" @click="save">Zapisz</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import {notification} from "../../Notifications";

  export default {
    name: "workers-create",
    data() {
      return {
        rules: {
          required: value => !!value || "To pole jest wymagane",
          emailRules: v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Niepoprawny adres email',
          phoneMax12: value => value.length <= 12 || 'Numer telefonu powinien mieć mniej niż 13 znaków',
          postCodeFormat: value => /^\d{2}-\d{3}$/.test(value) || 'Nieprawidłowy format kodu pocztowego',
          passwordMax6: value => value.length >= 6 || 'Hasło może mieć maksymalnie 6 znaków'
        },
        loading: false,
        form: {
          name: '',
          surname: '',
          email: '',
          address: {
            street: '',
            houseNumber: '',
            apartmentNumber: '',
            city: '',
            postCode: '',
          },
          phone: '',
          password: '',
        },
        errors: {
          name: [],
          surname: [],
          email: [],
          address: {
            street: [],
            houseNumber: [],
            apartmentNumber: [],
            city: [],
            postCode: [],
          },
          phone: [],
          password: [],
        }
      };
    },
    methods: {
      cancel() {
        window.location.replace(route('worker.index'));
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
      save() {
        if (this.$refs.form.validate()) {
          this.loading = true;
          let data = {};
          let entries = Object.entries(this.form);
          for (let [key, v] of entries) {
            let value = v;
            data[key] = value;
          }
          axios.post(route('api.user.storeWorker'), data).then(response => {
            notification(response.data.message, 'success');
            window.location.replace(route('worker.index'));
          }).catch(error => {
            console.error(error.response);
            if (error.response.statusCode === 500) {
              notification(error.response.data.message, 'error');
            } else {
              notification('Wystąpił błąd podczas dodawania pracownika', 'error');
              this.fillErrors(error);
            }
          }).finally(() => {
            this.loading = false;
          })
        }
      }
    }
  }
</script>

<style scoped>

</style>