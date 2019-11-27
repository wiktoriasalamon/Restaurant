<template>
	<v-row class="justify-center align-center">
    <v-col>
      <v-card class="transparent_form">
        <v-card-title>
          <h1>Stoliki</h1>
        </v-card-title>
      
		<v-data-table
			:headers="headers"
			:items="tables"
			:items-per-page="-1"
			class="elevation-1"
		>
			<template slot="item" slot-scope="props">
				<tr>
					<td class="text-xs-left">{{ props.item.table.id }}</td>
					<td class="text-xs-left">{{ props.item.table.size}}</td>
					<td class="text-xs-left">{{ props.item.table.occupied_since}}</td>
					<td class="text-xs-left">{{ props.item.reservation_since}}</td>
					<td class="text-xs-left">
						<v-btn @click="addOrder(props.item.table.id)" v-if="props.item.table.occupied_since!==null">
							Dodaj
						</v-btn>
					</td>
					<td class="text-xs-left">
						<v-btn @click="openTable(props.item.table.id)" v-if="props.item.table.occupied_since===null">
							Otwórz stolik
						</v-btn>
						<v-btn @click="closeTable(props.item.table.id)" v-else>
							Zamknij stolik
						</v-btn>
					</td>
					<td class="text-xs-center">
						<v-icon @click="showItem(props.item.table.id)" small>
							visibility
						</v-icon>
					</td>
				</tr>
			</template>
		</v-data-table>
    </v-card>
    </v-col>
	</v-row>
</template>

<script>
  import {notification} from '../../Notifications.js';
  export default {
    name: "waiter-tables-index",
    data() {
      return {
        tables: [],
        headers: [
          {text: 'Numer', value: 'id',},
          {text: 'Ilość osób', value: 'price'},
          {text: 'Zajęty od', value: ''},
          {text: 'Zarezerwowany od', value: ''},
          {text: 'Dodaj zamówienie', value: ''},
          {text: 'Otwórz/zamknij stolik', value: ''},
          {text: 'Akcje', value: ''},
        ],
      }
    },
    beforeMount() {
      this.getData()
    },
		created() {
			Echo.channel('table')
				.listen('TableChanged', (e) => {
					this.getData()
				});
			Echo.channel('reservation')
				.listen('ReservationChanged', (e) => {
					this.getData()
				});
			Echo.channel('order')
				.listen('OrderChanged', (e) => {
					this.getData()
				});
		},
    methods: {

      showItem(id) {
        window.location.href = route('table.showWaiter', [id])
      },
      getData() {
        axios.get(route('api.table.myTables'))
          .then(response => {
            this.tables = response.data
          }).catch(error => {
          console.error(error)
        })
      },
      addOrder(tableId){
				window.location.href = route('order.createWaiter', [tableId])
			},
      openTable(table){
        axios.post(route('api.table.openTable',table)).then(
          response => {
            notification("Stolik został otwarty","success")
            this.getData()
          },
          error => {
            console.error(error)
            notification("Wystąpił błąd podczas otwierania stolika","error")
          })
      },
      closeTable(table){
        axios.post(route('api.table.closeTable',table)).then(
          response => {
            notification("Stolik został zamknięty","success")
            setTimeout(function(){
              window.location.href=route('table.waiterIndex')} , 1500);
          },
          error => {
            console.error(error)
            notification("Wystąpił błąd podczas zamykania stolika","error")
          })
      },

    }
  }
</script>

<style scoped>

</style>