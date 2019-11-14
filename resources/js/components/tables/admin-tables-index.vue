<template>
  <v-row class="justify-center align-center">
    <v-data-table
        :headers="headers"
        :items="tables"
        :items-per-page="-1"
        class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar color="white" flat>
          <v-toolbar-title>Stoliki</v-toolbar-title>
          <v-divider
              class="mx-4"
              inset
              vertical
          ></v-divider>
          <v-spacer></v-spacer>
          <v-dialog max-width="500px" v-model="dialog">
            <template v-slot:activator="{ on }">
              <v-btn class="mb-2" color="primary" dark v-on="on">Dodaj stolik</v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-form
                        ref="form">
                      <v-text-field label="Wielkość stolika" v-bind:rules="rules"
                                    v-bind:error-messages="errors.size" v-model="editedItem.size"></v-text-field>
                    </v-form>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="close" color="blue darken-1" text>Anuluj</v-btn>
                <v-btn @click="save" color="blue darken-1" text>Zapisz</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template slot="item" slot-scope="props">
        <tr>
          <td class="text-xs-left">{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.size}}</td>
          <td class="text-xs-left">{{ props.item.occupied_since}}</td>
          <td class="text-xs-left">{{ props.item.reservation.length}}</td>
          <td class="text-xs-center">
            <v-icon @click="editItem(props.item)" small>
              edit
            </v-icon>
            <v-icon @click="deleteItem(props.item)" small>
              delete
            </v-icon>
          </td>
        </tr>
      </template>
    </v-data-table>
  </v-row>
</template>

<script>
  import {notification} from "../../Notifications";

  export default {
    name: "admin-tables-index",
    data() {
      return {
        dialog: false,
        editedIndex: -1,
        tables: [],
        headers: [
          {text: 'Numer', value: 'id',},
          {text: 'Ilość osób', value: 'price'},
          {text: 'Zajęty od', value: ''},
          {text: 'Ilość rezerwacji', value: ''},
          {text: 'Akcje', value: ''},
        ],
        editedItem: {
          id: '',
          size: ''
        },
        defaultItem: {
          id: '',
          size: ''
        },
        errors: {
          size: []
        },
        rules: [
          value => !!value || "To pole jest wymagane",
          value => /^\d+$/.test(value) || "To pole musi być liczbą"
        ]
      };
    },
    beforeMount() {
      this.getData()
    },
    methods: {
      deleteItem(item) {
        axios.delete(route('api.table.delete', item.id))
          .then(response => {
						notification("Pomyślnie usunięto stolik", 'success');
            this.getData()
          }).catch(error => {
					notification("Wystąpił błąd podczas usuwania stolika", 'error');
          console.error(error)
        })

      },
      editItem(item) {
        this.editedIndex = this.tables.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialog = true;
      },
      getData() {
        axios.get(route('api.table.index'))
          .then(response => {
            this.tables = response.data
          }).catch(error => {
          console.error(error)
        })
      },
      close() {
        this.clearErrors();
        this.dialog = false;
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem);
          this.$refs.form.reset();
          this.editedIndex = -1;
        }, 300)
      },
      fillErrors(data) {
        let entries = Object.entries(data);
        for (let [key, value] of entries) {
          this.errors[key] = value;
        }
      },
      clearErrors() {
        let keys = Object.keys(this.errors);
        for (let key of keys) {
          this.errors[key] = [];
        }
      },
      save() {
        if (this.$refs.form.validate() !== true) {
          return;
        }
        if (this.editedIndex > -1) {
          axios.post(route('api.table.update'), {
            id: this.editedItem.id,
            size: this.editedItem.size
          }).then(response => {
						notification("Pomyślnie edytowano stolik", 'success');
            this.getData();
            this.close();
          }).catch(error => {
            this.fillErrors(error.response.data.errors);
            notification("Wystąpił błąd podczas edytowania stolika", 'error');
            console.error(error.response);
          });
        } else {
          axios.post(route('api.table.store'), {size: this.editedItem.size})
            .then(response => {
							notification("Pomyślnie dodano stolik", 'success');
              this.getData();
              this.close();
            }).catch(error => {
							this.fillErrors(error.response.data.errors);
							notification("Wystąpił błąd podczas dodawania stolika", 'error');
							console.error(error.response);
          });
        }
      },
    },
    computed: {
      formTitle() {
        return this.editedIndex === -1 ? 'Nowy stolik' : 'Edycja stolika'
      },
    },
    watch: {
      dialog(val) {
        val || this.close()
      },
    },
  }
</script>

<style scoped>

</style>