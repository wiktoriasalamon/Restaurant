<template>
  <v-card>
    <v-card-title>Dodawanie pracownika</v-card-title>
    <v-card-text>
      <v-container>
        <v-form
          ref="form"
        >
          <v-text-field label="Imię" :rules="[rules.required]" v-model="form.name"></v-text-field>
          <v-text-field label="Nazwisko" :rules="[rules.required]" v-model="form.surname"></v-text-field>
          <v-text-field label="Email" :rules="[rules.required, rules.emailRules]" v-model="form.email"></v-text-field>
          <v-text-field label="Ulica" :rules="[rules.required]" v-model="form.address.street"></v-text-field>
          <v-text-field label="Numer domu" :rules="[rules.required]"
                        v-model="form.address.houseNumber"></v-text-field>
          <v-text-field label="Numer apartamentu" :rules="[rules.required]"
                        v-model="form.address.apartmentNumber"></v-text-field>
          <v-text-field label="Miasto" :rules="[rules.required]" v-model="form.address.city"></v-text-field>
          <v-text-field label="Kod pocztowy" :rules="[rules.required, rules.postCodeFormat]"
                        v-model="form.address.postCode"></v-text-field>
          <v-text-field label="Telefon" :rules="[rules.phoneMax12, rules.phoneNumber]"
                        v-model="form.phone"></v-text-field>
          <v-text-field label="Hasło" :rules="[rules.required]" v-model="form.name"></v-text-field>
          <v-text-field label="Powtórz hasło" :rules="[rules.required]"
                        v-model="form.name"></v-text-field>
        </v-form>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-btn>Anuluj</v-btn>
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
          postCodeFormat: value => /^\d{2}-\d{3}$/.test(value) || 'Nieprawidłowy format kodu pocztowego'
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
        }
      };
    },
    methods: {

    }
  }
</script>

<style scoped>

</style>