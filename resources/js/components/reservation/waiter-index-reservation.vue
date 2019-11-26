<template>
	<v-row>
		<v-row>
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
									v-on="on"
								></v-text-field>
							</template>
							<v-date-picker v-model="date" scrollable :min="minDate">
								<v-spacer></v-spacer>
								<v-btn text color="primary" @click="menu = false">Cancel</v-btn>
								<v-btn text color="primary" @click="$refs.menu.save(date)">OK</v-btn>
							</v-date-picker>
						</v-menu>
						<v-row class="justify-center">
							<v-btn @click="getReservations(date)" class="yellow_form_button" color="secondary">
								Wyszukaj
							</v-btn>
						</v-row>
					</v-card-text>
				</v-card>
			</v-col>
			<v-col cols="12" sm="10" md="8" lg="5" xl="4" class="button_row">
					<v-btn @click="addReservation" class="yellow_form_button darker_theme">
						Nowa rezerwacja
					</v-btn>
			</v-col>
		</v-row>
		<v-row style="width: 100%">
			<v-data-table
				:headers="headers"
				:items="reservations"
				:items-per-page="-1"
			>
				<template slot="item" slot-scope="props">
					<tr>
						<td class="text-xs-left">{{ props.item.reservation.start_time }}</td>
						<td class="text-xs-left">{{ props.item.size}}</td>
						<td class="text-xs-left">{{ props.item.reservation.email}}</td>
						<td class="text-xs-left">{{ props.item.status === 'current' ? "nadchodzący" : "archwilany"}}</td>
						<td class="text-xs-center">
							<v-icon @click="showItem(props.item.reservation.id)" small>
								visibility
							</v-icon>
							<v-icon @click="deleteItem(props.item.reservation.id)" small>
								delete
							</v-icon>
						</td>
					</tr>
				</template>
			</v-data-table>
		</v-row>
	</v-row>
	
</template>

<script>
  export default {
    name: "waiter-index-reservation",
    data() {
      return {
        menu: false,
        date: null,
        minDate: new Date().toISOString().substr(0, 10),
				reservations: [],
        headers: [
          { text: 'Początek rezerwacji', value: 'start_time',},
          { text: 'Ilość osób', value: 'size' },
          { text: 'Adres e-mail', value: 'email' },
          { text: 'Status', value: 'status' },
          { text: 'Akcje' },
        ],
      }
    },
		created() {
			Echo.channel('reservation')
				.listen('ReservationChanged', (e) => {
					if(this.date){
						this.getReservations(this.date)
					}
				});


		},
    methods:{
      addReservation(){
        window.location.href = route('reservation.create')
			},
			getReservations(date){
        axios.get(route('api.reservation.workerIndex', date))
          .then(response => {
            this.reservations = response.data.reservations;
						console.log(this.reservations);
          }).catch(error => {
          console.error(error)
        })
			},
			showItem(id){
        window.location.href = route('reservation.showWaiter', [id])
			},
			deleteItem(id){

			}
    }
  }
</script>

<style scoped>

	.button_row{
		align-items: center;
		display: flex;
	}
	.darker_theme{
		background-color: #BB936D !important;
	}
</style>