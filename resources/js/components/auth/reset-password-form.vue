<template>
	<v-row class="justify-center align-center">
		<v-col
			cols="12" lg="4" ma-2 md="5" sm="8" xl="3">
			<v-card class="transparent_form">
				<v-card-title>
					Fromularz zmiany hasła
				</v-card-title>
        <v-card-text>
          <v-form ref="resetPasswordForm">
            <v-text-field :rules="validation.password" label="Hasło" outlined v-model="form.password"></v-text-field>
            <v-text-field :rules="validation.repeatPassword" label="Powtórz hasło" outlined
                          v-model="form.repeatPassword"></v-text-field>
          </v-form>
					<v-card-actions>
						<v-spacer></v-spacer>
						<v-btn @click="save" class="yellow_form_button" color="secondary" v-bind:loading="loading">Zapisz</v-btn>
						<v-spacer></v-spacer>
					</v-card-actions>
        </v-card-text>
			</v-card>
		</v-col>
	</v-row>
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
        },
				loading: false
      };
    },
    methods: {
      save() {
        this.$refs.resetPasswordForm.validate();
        this.loading = true;
        axios.post(route('password.update'), {
          'newPassword': this.form.password,
          'newPasswordRepeated': this.form.repeatPassword,
          'token': this.token
        }).then((response) => {
          notification(response.data, "success");
          setTimeout(function () {
            window.location.href = "/"
          }, 2500);
        })
          .catch(error => {
            if (error.response.status === 422) {
              notification("Podano niepoprawne dane, spróbuj jeszcze raz", "error");
            } else{
              notification(error.response.data, "error")
						}

          }).finally(()=>{
            this.loading = false
				})

      }
    }
  }
</script>

<style scoped>

</style>