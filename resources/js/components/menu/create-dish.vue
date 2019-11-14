<template>
  <v-card>
    <v-card-title>Dodawanie dania</v-card-title>
    <v-card-text>
      <v-form
          ref="form">
        <v-text-field :rules="[rules.required]" label="Nazwa" v-bind:error-messages="errors.name"
                      v-model="form.name"
        ></v-text-field>
        <v-text-field :rules="[rules.required, rules.numeric]" label="Cena" v-bind:error-messages="errors.price"
                      v-model="form.price"
        ></v-text-field>
        <v-select
            :rules="[rules.required]"
            item-text="name"
            item-value="id"
            label="Kategoria"
            v-bind:error-messages="errors.category_id"
            v-bind:items="dishCategory"
            v-model="form.category_id">
        </v-select>
      </v-form>
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
    name: "create-dish",
    data() {
      return {
        rules: {
          required: value => !!value || "To pole jest wymagane",
          numeric: value => /^(\d+|\d+\.\d{1,2})$/.test(value) || 'Nieprawidłowy format ceny'
        },
        dishCategory: [],
        form: {
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
    },
    methods: {
      cancel() {
        window.location.replace(route('menu.admin'));
      },
      save() {
        if (this.$refs.form.validate()) {
          axios.post(route('api.dish.store'), this.form).then(response => {
            notification('Pomyślnie dodano danie', 'success');
            window.location.replace(route('menu.admin'));
          }).catch(error => {
            notification('Wystąpił błąd podczas zapisywania dania', 'error');
            console.error(error.response);
            let entries = Object.entries(error.response.data.errors);
            for (let [key, value] of entries) {
              this.errors[key] = value;
            }
          })
        }
      },
    }
  }
</script>

<style scoped>

</style>