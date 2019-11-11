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
  export default {
    name: "workers-index",
    data() {
      return {
        search: '',
        workers: [
          {
            id: 1,
            name: 'Name',
            surname: 'Surname',
            email: 'adksfj@gkj.co',
            address: 'we afj',
            phone: '505505505'
          }
        ],
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

    },
    methods: {
      addWorker() {
        window.location.replace(route('worker.create'));
      },
      editWorker(id) {
        window.location.replace(route('worker.edit', id));
      },
      deleteWorker(id) {
        console.log('delete ' + id);
      }
    }
  }
</script>

<style scoped>

</style>