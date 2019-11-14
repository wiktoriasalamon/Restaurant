<template>
  <v-card>
    <v-col class="justify-center align-center">
      <v-card-title>
        Dania
        <v-spacer></v-spacer>
        <v-btn @click="addDish">Dodaj danie</v-btn>
      </v-card-title>
      <v-card-text>
        <v-data-table
            :headers="headers"
            :items="menuItems"
            :items-per-page="-1"
            class="elevation-1"
        >
          <template slot="item" slot-scope="props">
            <tr>
              <td class="text-xs-left">{{ props.item.name }}</td>
              <td class="text-xs-left">{{ props.item.price}}</td>
              <td class="text-xs-center">
                <v-icon @click="editItem(props.item.id)" small>
                  edit
                </v-icon>
                <v-icon @click="deleteItem(props.item)" small>
                  delete
                </v-icon>
              </td>
            </tr>
          </template>
        </v-data-table>
      </v-card-text>
    </v-col>
  </v-card>
</template>

<script>
  import {notification} from "../../Notifications";

  export default {
    name: "admin-menu",
    data() {
      return {
        menuItems: [],
        headers: [
          {text: 'Nazwa', value: 'name',},
          {text: 'Cena', value: 'price'},
          {text: 'Akcje', value: ''},
        ],
      }
    },
    beforeMount() {
      this.getData()
    },
    methods: {
      deleteItem(item) {
        axios.delete(route('api.dish.delete', item.id))
          .then(response => {
            notification('Pomyślnie usunięto danie', 'success');
            this.getData()
          }).catch(error => {
            notification('Wystąpił błąd podczas usuwania dania', 'error');
          console.error(error)
        })

      },
      editItem(id) {
        window.location.href = route('dish.edit', [id])
      },
      getData() {
        axios.get(route('api.dish.index'))
          .then(response => {
            this.menuItems = response.data
          }).catch(error => {
          console.error(error)
        })
      },
      addDish() {
        window.location.replace(route('dish.create'));
      }
    }
  }
</script>

<style scoped>

</style>