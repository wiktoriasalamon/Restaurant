<template>
	<v-row>
		<v-row>
			<v-col>
				<v-card>
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
						<v-btn @click="getReservations(date)">
							Wyszukaj
						</v-btn>
					</v-card-text>
				</v-card>
			</v-col>
			<v-col>
				<v-btn @click="addReservation">
					Dodaj rezerwację
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
						<td class="text-xs-left">{{ props.item.reservation.table_id}}</td>
						<td class="text-xs-left">{{ props.item.reservation.email}}</td>
						<td class="text-xs-left">{{ props.item.status}}</td>
						<td class="text-xs-center">
							<v-icon @click="editItem(props.item.reservation.id)" small>
								edit
							</v-icon>
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
          { text: 'Numer stolika', value: 'table_id' },
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
            this.reservations = response.data.reservations
          }).catch(error => {
          console.error(error)
        })
			},
			editItem(id){

			},
			showItem(id){

			},
			deleteItem(id){

			}
    }
  }
</script>

<style scoped>

</style>