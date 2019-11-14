<template>
	<v-row class="justify-center align-center">
		<v-data-table
			:headers="headers"
			:items="tables"
			:items-per-page="-1"
			class="elevation-1"
		>
			<template slot="item" slot-scope="props">
				<tr>
					<td class="text-xs-left">{{ props.item.id }}</td>
					<td class="text-xs-left">{{ props.item.size}}</td>
					<td class="text-xs-left">{{ props.item.occupied_since}}</td>
					<td class="text-xs-left">{{ props.item.reservation.length}}</td>
					<td class="text-xs-left">
						<v-btn @click="addOrder(props.item.id)" v-if="props.item.occupied_since!==null">
							Dodaj
						</v-btn>
					</td>
					<td class="text-xs-left">
						<v-btn @click="openTable(props.item.id)" v-if="props.item.occupied_since===null">
							Otwórz stolik
						</v-btn>
						<v-btn @click="closeTable(props.item.id)" v-else>
							Zamknij stolik
						</v-btn>
					</td>
					<td class="text-xs-center">
						<v-icon @click="showItem(props.item.id)" small>
							visibility
						</v-icon>
					</td>
				</tr>
			</template>
		</v-data-table>
	</v-row>
</template>

<script>
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
    methods: {
      showItem(id) {
        window.location.href = route('table.showWaiter', [id])
      },
      getData() {
        axios.get(route('api.table.index'))
          .then(response => {
            this.tables = response.data

          }).catch(error => {
          console.error(error)
        })
      },
      addOrder(tableId){
				window.location.href = route('order.createWaiter', [tableId])
			},
			openTable(id){

			},
			closeTable(id){
			}

    }
  }
</script>

<style scoped>

</style>