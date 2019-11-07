<template>
	<v-row class="justify-center align-center">
		<v-data-table
			:headers="headers"
			:items="dishes"
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
		props: ['dishes'],
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
      this.menuItems = this.dishes
    },
		methods:{
      //TODO jak będzie api
      deleteItem(id){
        // axios.delete('api/users', {params: {'id': this.checkedNames})
			},
			editItem(id){
				window.location.href = route('dish.edit',  [id])
			}
		}
  }
</script>

<style scoped>

</style>