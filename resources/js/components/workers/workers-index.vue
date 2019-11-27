<template>
	<v-row class="justify-center align-center">
		<v-col cols="12" lg="8" ma-2 md="10" sm="12" xl="6">
			<v-card class="transparent_form">
				<v-card-title>
					Pracownicy
					<v-spacer></v-spacer>
					<v-text-field
						v-model="search"
						append-icon="search"
						label="Wyszukaj"
						single-line
						hide-details
					></v-text-field>
					<v-spacer></v-spacer>
					<v-btn @click="addWorker" class="yellow_form_button" color="secondary">Dodaj pracownika</v-btn>
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
		</v-col>
	</v-row>
</template>

<script>
  import {notificationError, notificationSuccess} from "../../Notifications";

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
          notificationSuccess(response.data);
          window.location.replace(route('worker.index'));
        }).catch(error => {
          console.error(error.response);
          notificationError(error.response.data);
        });
      }
    }
  }
</script>

<style scoped>

</style>