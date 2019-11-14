<template>
  <v-card>
    <v-card-title>Edytowanie pracownika</v-card-title>
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
          <v-text-field :rules="[rules.required]" label="Numer domu"
                        v-bind:error-messages="errors.address"
                        v-model="form.address.houseNumber"></v-text-field>
          <v-text-field :rules="[rules.required]" label="Numer apartamentu"
                        v-bind:error-messages="errors.address"
                        v-model="form.address.apartmentNumber"></v-text-field>
          <v-text-field :rules="[rules.required]" label="Miasto" v-bind:error-messages="errors.address"
                        v-model="form.address.city"></v-text-field>
          <v-text-field :rules="[rules.required, rules.postCodeFormat]" label="Kod pocztowy"
                        v-bind:error-messages="errors.address" v-model="form.address.postCode"></v-text-field>
          <v-text-field :rules="[rules.phoneMax12]" label="Telefon"
                        v-bind:error-messages="errors.phone" v-model="form.phone"></v-text-field>
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
    props: ['id'],
    name: "workers-edit",
    data() {
      return {
        rules: {
          required: value => !!value || "To pole jest wymagane",
          emailRules: v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Niepoprawny adres email',
          phoneMax12: value => value.length <= 12 || 'Numer telefonu powinien mieć mniej niż 13 znaków',
          postCodeFormat: value => /^\d{2}-\d{3}$/.test(value) || 'Nieprawidłowy format kodu pocztowego',
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
        },
        errors: {
          name: [],
          surname: [],
          email: [],
          address: [],
          phone: [],
        }
      };
    },
    beforeMount() {
      axios.get(route('api.user.fetchUser', this.id)).then(response => {
        let entries = Object.entries(response.data);
        for (let [key, v] of entries) {
          let value = v;
          if (key == 'address') {
            value = JSON.parse(value);
          }
          this.form[key] = value;
        }
      }).catch(error => {
        console.error(error.response);
      });
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
          axios.put(route('api.user.updateWorker', this.id), data).then(response => {
            notification('Pomyślnie edytowano pracownika', 'success');
            window.location.replace(route('worker.index'));
          }).catch(error => {
            notification('Wystąpił błąd podczas edytowania pracownika', 'error');
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