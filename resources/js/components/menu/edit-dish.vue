<template>
	<v-row class="justify-center align-center">
		<v-col cols="12" lg="5" ma-2 md="8" sm="10" xl="4">
			<v-card class="transparent_form">
				<v-card-title>Edytowanie dania</v-card-title>
				<v-card-text>
					<v-form
						ref="form">
						<v-text-field :rules="[rules.required]" label="Nazwa" v-bind:error-messages="errors.name"
													v-model="form.name" outlined
						></v-text-field>
						<v-text-field :rules="[rules.required, rules.numeric]" label="Cena" v-bind:error-messages="errors.price"
													v-model="form.price" outlined
						></v-text-field>
						<v-select
							:rules="[rules.required]"
							item-text="name"
							item-value="id"
							label="Kategoria"
              outlined
							v-bind:error-messages="errors.category_id"
							v-bind:items="dishCategory"
							v-model="form.category_id">
						</v-select>
					</v-form>
				</v-card-text>
				<v-card-actions>
					<v-row class="justify-space-between">
						<v-btn @click="cancel" text>Anuluj</v-btn>
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
    name: "edit-dish",
    data() {
      return {
        loading: false,
        rules: {
          required: value => !!value || "To pole jest wymagane",
          numeric: value => /^(\d+|\d+\.\d{1,2})$/.test(value) || 'Nieprawidłowy format ceny'
        },
        dishCategory: [],
        form: {
          id: '',
          name: '',
          price: '',
          category_id: ''
        },
        errors: {
          name: [],
          price: [],
          category_id: []
        }
      };
    },
    beforeMount() {
      axios.get(route('api.dishCategory.index')).then(response => {
        this.dishCategory = response.data;
      }).catch(error => {
        console.error(error.response);
      });
      axios.get(route('api.dish.load', this.id)).then(response => {
        let keys = Object.keys(this.form);
        for (let key of keys) {
          this.form[key] = response.data[key];
        }
      }).catch(error => {
        console.error(error.response);
      });
    },
    methods: {
      cancel() {
        window.location.replace(route('menu.admin'));
      },
      clearErrors(object) {
        let keys = Object.keys(object);
        for (let key of keys) {
          object[key] = [];
        }
      },
      fillErrors(error) {
        this.clearErrors(this.errors);
        let entries = Object.entries(error.response.data.errors);
        for (let [key, value] of entries) {
          this.errors[key] = value;
        }
      },
      save() {
        if (this.$refs.form.validate()) {
          this.loading = true;
          this.clearErrors(this.errors);
          axios.post(route('api.dish.update'), this.form).then(response => {
            notification('Pomyślnie edytowano danie', 'success');
            window.location.replace(route('menu.admin'));
          }).catch(error => {
            console.error(error.response);
            if (error.response.statusCode === 500) {
              notificationError(error.response.data);
            } else {
              notification('Wystąpił błąd poczas edytowania dania', 'error');
              this.fillErrors(error);
            }
          }).finally(() => {
            this.loading = false;
          })
        }
      },
    }
  }
</script>

<style scoped>

</style>