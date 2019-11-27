<template>
	<v-row class="justify-space-around">
		<v-col cols="12" sm="10" md="8" lg="5" xl="4">
			<v-card class="transparent_form">
				<v-card-text>
					<h4>W celu wyszukania rezerwacji podaj datę:</h4>
					<v-menu
						ref="menu"
						v-model="menu"
						:close-on-content-click="false"
						:return-value.sync="date"
						transition="scale-transition"
						offset-y
						min-width="290px"
					>
						<template v-slot:activator="{ on }">
							<v-text-field
								v-model="date"
								label="Wybierz datę"
								append-icon="event"
								readonly
								outlined
								v-on="on"
							></v-text-field>
						</template>
						<v-date-picker v-model="date" scrollable :min="minDate" first-day-of-week="1" locale="pl">
							<v-spacer></v-spacer>
							<v-btn text color="primary" @click="menu = false">Anuluj</v-btn>
							<v-btn text color="primary" @click="$refs.menu.save(date)">Wybierz</v-btn>
						</v-date-picker>
					</v-menu>
					<v-row class="justify-center">
						<v-btn @click="getReservations(date)" v-bind:loading="loading" :disabled="date == null" class="yellow_form_button" color="secondary">
							Wyszukaj
						</v-btn>
					</v-row>
				</v-card-text>
			</v-card>
			<v-data-table
				:headers="headers"
				:items="reservations"
				:items-per-page="5"
				:no-data-text="nodata"
			>
				<template slot="item" slot-scope="props">
					<tr>
						<td class="text-xs-left">{{ props.item.reservation.start_time }}</td>
						<td class="text-xs-left">{{ props.item.size}}</td>
						<td class="text-xs-left">{{ props.item.reservation.email}}</td>
						<td class="text-xs-left">{{ props.item.status === 'current' ? "nadchodzący" : "archwilany"}}</td>
						<td class="text-xs-center">
							<v-icon @click="deleteItem(props.item.reservation.id)" small>
								delete
							</v-icon>
						</td>
					</tr>
				</template>
			</v-data-table>
		</v-col>
		<v-col cols="12" sm="10" md="8" lg="5" xl="4" class="button_row">

			<v-card class="transparent_form">
				<v-card-text>
					<h4>Złoż nową rezerwację:</h4><br>
					<v-row class="justify-center">
						<v-btn @click="addReservation" class="yellow_form_button darker_theme">
							Nowa rezerwacja
						</v-btn>
					</v-row>
				</v-card-text>
			</v-card>
		</v-col>
	</v-row>

</template>

<script>
  import {notification} from '../../Notifications.js';
  export default {
    name: "waiter-index-reservation",
    data() {
      return {
        menu: false,
        date: null,
        minDate: new Date().toISOString().substr(0, 10),
        reservations: [],
        headers: [
          {text: 'Początek rezerwacji', value: 'start_time',},
          {text: 'Ilość osób', value: 'size'},
          {text: 'Adres e-mail', value: 'email'},
          {text: 'Status', value: 'status'},
          {text: 'Akcje'},
        ],
        disabledButton: true,
				loading: false,
				nodata:"Brak danych"
      }
    },
    created() {
      Echo.channel('reservation')
        .listen('ReservationChanged', (e) => {
          if (this.date) {
            this.getReservations(this.date)
          }
        });


    },
    methods: {
      addReservation() {
        window.location.href = route('reservation.create')
      },
      getReservations(date) {
        this.loading = true;
        axios.get(route('api.reservation.workerIndex', date))
          .then(response => {
            this.reservations = response.data.reservations;

            	this.nodata="Brak rezerwacji"

            console.log(this.reservations);
          }).catch(error => {
          console.error(error)
        }).finally(()=>{
          this.loading = false
				})
      },
      deleteItem(id) {
        axios.delete(route('api.reservation.delete', {
          id: id
        }))
          .then(response => {
            this.getReservations(this.date)
            notification(response.data, 'success');
          }).catch(error => {
          notification("Wystąpił błąd podczas usuwania rezerwacji", 'error');
          console.error(error.response);
        });
      }
    }
  }
</script>

<style scoped>

	.button_row {
		align-items: center;
		display: flex;
	}

	.darker_theme {
		background-color: #BB936D !important;
	}
</style>