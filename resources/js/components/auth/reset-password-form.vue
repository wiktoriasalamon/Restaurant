<template>
  <v-form
    ref="resetPasswordForm"
  >
    <v-container>
      <v-col md="5">
        <v-text-field :rules="validation.password" label="Hasło" v-model="form.password"></v-text-field>
        <v-text-field :rules="validation.repeatPassword" label="Powtórz hasło"
                      v-model="form.repeatPassword"></v-text-field>
        <v-btn @click="save">Zapisz</v-btn>
      </v-col>
    </v-container>
  </v-form>
</template>

<script>
  import {notification} from '../../Notifications.js';

  export default {
    name: "reset-password-form",
    props: ['token'],
    data() {
      return {
        form: {
          password: '',
          repeatPassword: '',
        },
        validation: {
          password: [
            v => !!v || 'Pole jest wymagane',
            v => (v && v.length >= 6) || 'Hasło musi mieć przynajmniej 6 znaków'
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
        axios.post(route('password.update'), {
          'newPassword': this.form.password,
          'newPasswordRepeated': this.form.repeatPassword,
          'token': this.token
        }).then((response) => {
          notification(response.data, "success")
          setTimeout(function(){window.location.href="/"} , 2500);
        })
          .catch(error => {
            notification(error.response.data, "error")
          });

      }
    }
  }
</script>

<style scoped>

</style>