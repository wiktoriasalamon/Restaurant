<template>
  <v-form
  ref="resetPasswordForm"
  >
    <v-container>
      <v-col md="5">
        <v-text-field :rules="validation.password" label="Hasło" v-model="form.password"></v-text-field>
        <v-text-field :rules="validation.repeatPassword" label="Powtórz hasło" v-model="form.repeatPassword"></v-text-field>
        <v-btn @click="save">Zapisz</v-btn>
      </v-col>
    </v-container>
  </v-form>
</template>

<script>
  export default {
    name: "reset-password-form",
    data() {
      return {
        form: {
          password: '',
          repeatPassword: '',
        },
        validation: {
          password: [
            v => !!v || 'Pole jest wymagane',
            v => (v && v.length >= 8) || 'Hasło musi mieć przynajmniej 8 znaków'
          ],
          repeatPassword: [
            v => !!v || 'Pole jest wymagane',
            v => (v && v === this.form.password) || 'Hasła muszą być takie same'
          ]
        }
      };
    },
    methods: {
      save() {
        this.$refs.resetPasswordForm.validate()
      }
    }
  }
</script>

<style scoped>

</style>