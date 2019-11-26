<template>
	<v-row class="justify-center align-center">
		<v-data-table
			:headers="headers"
			:items="reservations"
			:items-per-page="5"
			class="elevation-1"
		>
			<template slot="item" slot-scope="props">
				<tr>
					<td class="text-xs-left">{{ props.item.reservation.date }}</td>
					<td class="text-xs-left">{{ props.item.reservation.start_time}}</td>
					<td class="text-xs-left">{{props.item.reservation.table_id }}</td>
					<td class="text-xs-left">
						<v-icon @click="show(props.item.reservation.id)"  small>
							visibility
						</v-icon>
						<v-icon @click="cancelReservation(props.item.reservation.id)"  small>
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
    name: "user-index-reservation",
    data() {
      return {
        reservations:[],
        headers: [
          { text: 'Data', value: 'date',},
          { text: 'Godzina', value: 'start_time' },
          { text: 'Numer stolika', value: 'table_id' },
          { text: 'Akcje', value: 'action' },
        ],
      }
    },
		beforeMount(){
      this.getReservations()
		},
		created() {
			Echo.channel('reservation')
				.listen('ReservationChanged', (e) => {
						this.getReservations()
				});
		},
		methods:{
      getReservations(){
        axios.get(route('api.reservation.customerIndex'))
          .then((response) => {
            this.reservations = response.data.reservations
          }).catch((error) => {
          	console.error(error)
        })
			},
			show(id) {
      	window.location.replace(route('reservation.showUser', id));
			},
			cancelReservation(id) {
				axios.delete(route('api.reservation.delete', id)).then(response => {
					notification(response.data, 'success');
					window.location.replace(route('reservation.indexUser'));
				}).catch(error => {
					console.error(error);
					notification(error.response.data, 'error');
				})
			}
		}
  }
</script>

<style scoped>

</style>