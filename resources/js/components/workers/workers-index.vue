<template>
  <v-card>
    <v-card-title>
      Pracownikcy
      <v-spacer></v-spacer>
      <v-text-field
          v-model="search"
          append-icon="search"
          label="Search"
          single-line
          hide-details
      ></v-text-field>
      <v-spacer></v-spacer>
      <v-btn @click="addWorker">Dodaj pracownika</v-btn>
    </v-card-title>
    <v-data-table
        :headers="headers"
        :items="workers"
        :search="search"
    >
      <template v-slot:item.action="{ item }">
        <v-icon
            small
            class="mr-2"
            @click="editWorker(item.id)"
        >
          edit
        </v-icon>
        <v-icon
            small
            @click="deleteWorker(item.id)"
        >
          delete
        </v-icon>
      </template>

    </v-data-table>
  </v-card>
</template>

<script>
  import {notification} from "../../Notifications";

  export default {
    name: "workers-index",
    data() {
      return {
        search: '',
        workers: [],
        headers: [
          {
            text: 'ID',
            value: 'id'
          },
          {
            text: 'Imię',
            value: 'name'
          },
          {
            text: 'Nazwisko',
            value: 'surname'
          },
          {
            text: 'E-mail',
            value: 'email'
          },
          {
            text: 'Adres',
            value: 'address',
            sortable: false
          },
          {
            text: 'Telefon',
            value: 'phone'
          },
          {
            text: 'Akcje',
            value: 'action',
            sortable: false
          },
        ],
      };
    },
    beforeMount() {
      axios.get(route('api.user.fetchWorkers')).then(result => {
        this.workers = result.data;
        let workersEntries = Object.entries(result.data);
        for (let [workerId, workerValue] of workersEntries) {
          let entries = Object.entries(JSON.parse(workerValue.address));
          this.workers[workerId].address = '';
          for (let [key, value] of entries) {
            this.workers[workerId].address += value;
            if (key != 'postCode') {
              this.workers[workerId].address += ', ';
            }
          }
        }
      }).catch(error => {
        console.error(error.response);
      });
    },
    methods: {
      addWorker() {
        window.location.replace(route('worker.create'));
      },
      editWorker(id) {
        window.location.replace(route('worker.edit', id));
      },
      deleteWorker(id) {
        axios.delete(route('api.user.delete', id)).then(response => {
          notification('Pomyślnie usunięto pracownika', 'success');
          window.location.replace(route('worker.index'));
        }).catch(error => {
          notification('Wystąpił błąd podczas usuwania pracownika', 'error');
          console.error(error.response);
        });
      }
    }
  }
</script>

<style scoped>

</style>