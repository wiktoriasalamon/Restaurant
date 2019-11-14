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
          <v-text-field :rules="[rules.required]" label="Ulica" v-bind:error-messages="errors.address"
                        v-model="form.address.street"></v-text-field>
          <v-text-field label="Numer domu" :rules="[rules.required]"
                        v-bind:error-messages="errors.address" v-model="form.address.houseNumber"></v-text-field>
          <v-text-field label="Numer apartamentu" :rules="[rules.required]"
                        v-bind:error-messages="errors.address"
                        v-model="form.address.apartmentNumber"></v-text-field>
          <v-text-field :rules="[rules.required]" label="Miasto" v-bind:error-messages="errors.address"
                        v-model="form.address.city"></v-text-field>
          <v-text-field label="Kod pocztowy" :rules="[rules.required, rules.postCodeFormat]"
                        v-bind:error-messages="errors.address" v-model="form.address.postCode"></v-text-field>
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
      <v-btn @click="save">Zapisz</v-btn>
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
          address: [],
          phone: [],
          password: [],
        }
      };
    },
    methods: {
      cancel() {
        window.location.replace(route('worker.index'));
      },
      save() {
        if (this.$refs.form.validate()) {
          let data = {};
          let entries = Object.entries(this.form);
          for (let [key, v] of entries) {
            let value = v;
            if (key == 'address') {
              value = JSON.stringify(value);
            }
            data[key] = value;
          }
          axios.post(route('api.user.storeWorker'), data).then(response => {
            notification('Pomyślnie dodano pracownika', 'success');
            window.location.replace(route('worker.index'));
          }).catch(error => {
            notification('Wystąpił błąd podczas dodawania pracownika', 'error');
            console.error(error.response);
            let entries = Object.entries(error.response.data.errors);
            for (let [key, value] of entries) {
              this.errors[key] = value;
            }
          });
        }
      }
    }
  }
</script>

<style scoped>

</style>