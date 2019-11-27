<template>
	<v-row class="justify-space-around">
		<v-col
			cols="12" lg="4" ma-2 md="5" sm="8" xl="3">
			<v-card class="transparent_form">
				<v-card-title>Edytowanie pracownika</v-card-title>
				<v-card-text>
					<v-container>
						<v-form
							ref="form"
						>
							<v-text-field :rules="[rules.required]" label="Imię" v-bind:error-messages="errors.name" outlined
														v-model="form.name"></v-text-field>
							<v-text-field :rules="[rules.required]" label="Nazwisko" v-bind:error-messages="errors.surname" outlined
														v-model="form.surname"></v-text-field>
							<v-text-field :rules="[rules.required, rules.emailRules]" outlined label="Email"
														v-bind:error-messages="errors.email"
														v-model="form.email"></v-text-field>
							<v-text-field :rules="[rules.required]" label="Ulica" v-bind:error-messages="errors.address" outlined
														v-model="form.address.street"></v-text-field>
							<v-text-field :rules="[rules.required]" label="Numer domu" outlined
														v-bind:error-messages="errors.address"
														v-model="form.address.houseNumber"></v-text-field>
							<v-text-field :rules="[rules.required]" label="Numer apartamentu" outlined
														v-bind:error-messages="errors.address"
														v-model="form.address.apartmentNumber"></v-text-field>
							<v-text-field :rules="[rules.required]" label="Miasto" v-bind:error-messages="errors.address" outlined
														v-model="form.address.city"></v-text-field>
							<v-text-field :rules="[rules.required, rules.postCodeFormat]" label="Kod pocztowy" outlined
														v-bind:error-messages="errors.address" v-model="form.address.postCode"></v-text-field>
							<v-text-field :rules="[rules.phoneMax12]" label="Telefon" outlined
														v-bind:error-messages="errors.phone" v-model="form.phone"></v-text-field>
						</v-form>
					</v-container>
				</v-card-text>
				<v-card-actions>
					<v-row class="justify-space-between">
						<v-btn @click="cancel">Anuluj</v-btn>
						<v-btn @click="save" v-bind:loading="loading" class="yellow_form_button" color="secondary">Zapisz</v-btn>
					</v-row>

				</v-card-actions>
			</v-card>
		</v-col>
	</v-row>
</template>

<script>
  import {notification} from "../../Notifications";

  export default {
    props: ['id'],
    name: "workers-edit",
    data() {
      return {
        loading: false,
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
          axios.put(route('api.user.updateWorker', this.id), data).then(response => {
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
          });
        }
      }
    }
  }
</script>

<style scoped>

</style>