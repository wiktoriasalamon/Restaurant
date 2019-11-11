<template>
  <v-card>
    <v-card-title>Dodawanie pracownika</v-card-title>
    <v-card-text>
      <v-container>
        <v-form
            ref="form"
        >
          <v-text-field :rules="[rules.required]" error-messages="errors.name" label="Imię"
                        v-model="form.name"></v-text-field>
          <v-text-field :rules="[rules.required]" error-messages="errors.surname" label="Nazwisko"
                        v-model="form.surname"></v-text-field>
          <v-text-field :rules="[rules.required, rules.emailRules]" error-messages="errors.email" label="Email"
                        v-model="form.email"></v-text-field>
          <v-text-field :rules="[rules.required]" error-messages="errors.address.street" label="Ulica"
                        v-model="form.address.street"></v-text-field>
          <v-text-field label="Numer domu" :rules="[rules.required]"
                        error-messages="errors.address.houseNumber" v-model="form.address.houseNumber"></v-text-field>
          <v-text-field label="Numer apartamentu" :rules="[rules.required]"
                        error-messages="errors.address.apartmentNumber"
                        v-model="form.address.apartmentNumber"></v-text-field>
          <v-text-field :rules="[rules.required]" error-messages="errors.address.city" label="Miasto"
                        v-model="form.address.city"></v-text-field>
          <v-text-field label="Kod pocztowy" :rules="[rules.required, rules.postCodeFormat]"
                        error-messages="errors.address.postCode" v-model="form.address.postCode"></v-text-field>
          <v-text-field :rules="[rules.phoneMax12]" error-messages="errors.phone"
                        label="Telefon" v-model="form.phone"></v-text-field>
          <v-text-field :rules="[rules.required, rules.passwordMax6]" error-messages="errors.password" label="Hasło"
                        v-model="form.name"></v-text-field>
        </v-form>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-btn @click="cancel">Anuluj</v-btn>
      <v-btn>Zapisz</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
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
        window.location.replace(route(worker.index));
      },
      save() {
        let data = this.form;
        data.address = JSON.stringify(data.address);
        axios.post(route(), data).then(response => {
          windows.location.replace(route(worker.index));
        }).catch(error => {
          let entries = Object.entries(error.response.data.messages);
          for (let [key, value] of entries) {
            this.error[key] = value;
          }
        });
      }
    }
  }
</script>

<style scoped>

</style>