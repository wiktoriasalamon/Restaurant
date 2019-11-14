<template>
	<v-row class="justify-center">
		<v-card>
			<v-card-title>
				<h2>Edycja zamówienia</h2>
			</v-card-title>
			<v-card-text>
				<h5 style="margin-bottom: 2rem;">Status zamówienia:  {{orderStatus}}</h5>
				<v-data-table
					:headers="orderedItemsHeaders"
					:items="orderedItems"
					:items-per-page="-1"
					class="elevation-1"
				>
					<template slot="item" slot-scope="props">
						<tr>
							<td class="text-xs-left">{{ props.item.name }}</td>
							<td class="text-xs-left">{{ props.item.price}}</td>
							<td class="text-xs-left">{{props.item.amount}}</td>
						</tr>
					</template>
				</v-data-table>
				<h5 style="margin-top: 2rem;">Suma zamówienia: {{orderSum}}</h5>
			</v-card-text>
			<v-card-actions>
				<v-btn @click="goHome">
					Powrót do strony głównej
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-row>
</template>

<script>
  export default {
    name: "order-show",
    props:['token'],
    data() {
      return {
        orderedItemsHeaders:[
          { text: 'Nazwa', value: 'name',},
          { text: 'Cena', value: 'price' },
          { text: 'Ilość', value: '' },
        ],
        orderedItems:[],
        orderStatus:'',
        orderSum: ''
      }
    },
    beforeMount(){
      this.getOrder();
    },
    methods: {
      getOrder(){
        axios.get(route('api.order.loadOrder', this.token))
          .then(response => {
            this.orderedItems = response.data.dishes;
            this.orderSum = response.data.sum;
            this.orderStatus = response.data.status
            console.log(response)
          }).catch(error => {
          console.error(error)
        })
      },
			goHome(){
        window.location.href = route('home')
			}
    }
  }
</script>

<style scoped>

</style>