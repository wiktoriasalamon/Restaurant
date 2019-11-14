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
				</tr>
			</template>
		</v-data-table>
	</v-row>
</template>

<script>
  export default {
    name: "user-index-reservation",
    data() {
      return {
        reservations:[],
        headers: [
          { text: 'Data', value: 'date',},
          { text: 'Godzina', value: 'start_time' },
          { text: 'Numer stolika', value: 'table_id' },
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
          	console.log(error)
        })
			}
		}
  }
</script>

<style scoped>

</style>