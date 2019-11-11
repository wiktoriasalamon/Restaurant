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
					<td class="text-xs-center">
						<v-icon @click="editItem(props.item.id)" small>
							edit
						</v-icon>
						<v-icon @click="showItem(props.item.id)" small>
							visibility
						</v-icon>
						<v-icon @click="deleteItem(props.item)" small>
							delete
						</v-icon>
					</td>
				</tr>
			</template>
		</v-data-table>
	</v-row>
</template>

<script>
  export default {
    name: "admin-tables-index",
    data() {
      return {
        tables: [],
        headers: [
          {text: 'Numer', value: 'id',},
          {text: 'Ilość osób', value: 'price'},
          {text: 'Zajęty od', value: ''},
          {text: 'Ilość rezerwacji', value: ''},
          {text: 'Akcje', value: ''},
        ],
      }
    },
    beforeMount() {
      this.getData()
    },
    methods: {
      deleteItem(item) {
        axios.delete(route('api.table.delete', item.id))
          .then(response => {
            Vue.toasted.success(response.data).goAway(5000);
            this.getData()
          }).catch(error => {
          Vue.toasted.error(error.response.data).goAway(3000);
          console.error(error)
        })

      },
      editItem(id) {
        window.location.href = route('table.edit', [id])
      },
      showItem(id) {
        window.location.href = route('table.show', [id])
      },
      getData() {
        axios.get(route('api.table.index'))
          .then(response => {
            this.tables = response.data

          }).catch(error => {
          console.error(error)
        })
      }
    }
  }
</script>

<style scoped>

</style>