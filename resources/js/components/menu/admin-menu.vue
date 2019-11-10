<template>
	<v-row class="justify-center align-center">
		<v-data-table
			:headers="headers"
			:items="menuItems"
			:items-per-page="-1"
			class="elevation-1"
		>
			<template slot="item" slot-scope="props">
				<tr>
					<td class="text-xs-left">{{ props.item.name }}</td>
					<td class="text-xs-left">{{ props.item.price}}</td>
					<td class="text-xs-center">
						<v-icon @click="editItem(props.item.id)" small>
							edit
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
    name: "admin-menu",
    data() {
      return {
        menuItems:[],
        headers: [
          { text: 'Nazwa', value: 'name',},
          { text: 'Cena', value: 'price' },
          { text: 'Akcje', value: '' },
        ],
      }
    },
    beforeMount(){
     	this.getData()
    },
		methods:{
      //TODO jak będzie api
      deleteItem(id){
        // axios.delete('api/users', {params: {'id': this.checkedNames})
				this.getData()
			},
			editItem(id){
				window.location.href = route('dish.edit',  [id])
			},
			getData(){
        axios.get(route('api.dish.index'))
          .then(response => {
            this.menuItems = response.data
          }).catch(function (error) {
          	console.error(error)
        })
			}
		}
  }
</script>

<style scoped>

</style>